import axios from 'axios'
import * as cheerio from 'cheerio'
import { join, extname } from 'path'
import { createWriteStream } from 'fs'
import { imagesPath, Recipe } from './db'
import { v4 as uuidv4 } from 'uuid'

export const scraperService = {
  scrapeUrl: async (url: string): Promise<Partial<Recipe>> => {
    try {
      const response = await axios.get(url, {
        headers: {
          'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        }
      })
      const $ = cheerio.load(response.data)
      const ldJsonScripts = $('script[type="application/ld+json"]')
      
      let recipeData: any = null

      ldJsonScripts.each((_, element) => {
        try {
          const json = JSON.parse($(element).html() || '')
          
          // LD+JSON can be a single object, an array, or an @graph array
          const findRecipe = (obj: any): any => {
            if (Array.isArray(obj)) {
              for (const item of obj) {
                const found = findRecipe(item)
                if (found) return found
              }
            } else if (obj && typeof obj === 'object') {
              if (obj['@type'] === 'Recipe' || (Array.isArray(obj['@type']) && obj['@type'].includes('Recipe'))) {
                return obj
              }
              if (obj['@graph']) {
                return findRecipe(obj['@graph'])
              }
            }
            return null
          }

          recipeData = findRecipe(json)
          if (recipeData) return false // break loop
        } catch (e) {
          // ignore parse errors
        }
      })

      if (!recipeData) {
        // Fallback to OpenGraph or basic tags if needed
        return {
          title: $('title').text() || 'Unknown Recipe',
          sourceUrl: url,
          description: $('meta[property="og:description"]').attr('content') || ''
        }
      }

      // Convert instructions to list
      let instructions: string[] = []
      if (Array.isArray(recipeData.recipeInstructions)) {
        instructions = recipeData.recipeInstructions.map((step: any) => {
          if (typeof step === 'string') return step
          if (step.text) return step.text
          return ''
        }).filter(Boolean)
      } else if (typeof recipeData.recipeInstructions === 'string') {
        instructions = [recipeData.recipeInstructions]
      }

      // Extract image URL
      let imageUrl = ''
      if (Array.isArray(recipeData.image)) {
        imageUrl = typeof recipeData.image[0] === 'string' ? recipeData.image[0] : recipeData.image[0].url
      } else if (typeof recipeData.image === 'object' && recipeData.image.url) {
        imageUrl = recipeData.image.url
      } else if (typeof recipeData.image === 'string') {
        imageUrl = recipeData.image
      }

      return {
        title: recipeData.name || $('title').text(),
        sourceUrl: url,
        imageUrl: imageUrl,
        description: recipeData.description || '',
        ingredients: JSON.stringify(recipeData.recipeIngredient || []),
        instructions: JSON.stringify(instructions),
        prepTime: recipeData.prepTime,
        cookTime: recipeData.cookTime,
        servings: recipeData.recipeYield ? String(recipeData.recipeYield) : undefined
      }
    } catch (error) {
      console.error('Error scraping URL:', error)
      throw new Error('Failed to scrape the provided URL.')
    }
  },

  downloadImage: async (imageUrl: string): Promise<string | undefined> => {
    if (!imageUrl) return undefined
    try {
      const extension = extname(new URL(imageUrl).pathname) || '.jpg'
      const fileName = `${uuidv4()}${extension}`
      const localPath = join(imagesPath, fileName)
      
      const response = await axios({
        method: 'GET',
        url: imageUrl,
        responseType: 'stream',
        headers: {
          'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
          'Accept': 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8'
        }
      })

      const writer = createWriteStream(localPath)
      response.data.pipe(writer)

      return new Promise((resolve, reject) => {
        writer.on('finish', () => resolve(fileName))
        writer.on('error', reject)
      })
    } catch (error) {
      console.error('Error downloading image:', error)
      return undefined
    }
  }
}
