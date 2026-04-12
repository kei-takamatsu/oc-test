import { app, shell, BrowserWindow, ipcMain, protocol, net } from 'electron'
import { join } from 'path'
import { electronApp, optimizer, is } from '@electron-toolkit/utils'
import { dbService, imagesPath, Recipe } from './db'
import { cloudDbService } from './cloudDb'
import { authService, storageService } from './supabase'
import { scraperService } from './scraper'
import { aiService } from './ai'
import { pathToFileURL } from 'url'
import { writeFileSync } from 'fs'
import { v4 as uuidv4 } from 'uuid'

// Register protocol for local images
protocol.registerSchemesAsPrivileged([
  { scheme: 'recipe-image', privileges: { bypassCSP: true, stream: true } }
])

function createWindow(): void {
  const mainWindow = new BrowserWindow({
    width: 1100,
    height: 800,
    show: false,
    autoHideMenuBar: true,
    titleBarStyle: 'hiddenInset',
    webPreferences: {
      preload: join(__dirname, '../preload/index.js'),
      sandbox: false
    }
  })

  mainWindow.on('ready-to-show', () => {
    mainWindow.show()
  })

  mainWindow.webContents.setWindowOpenHandler((details) => {
    shell.openExternal(details.url)
    return { action: 'deny' }
  })

  if (is.dev && process.env['ELECTRON_RENDERER_URL']) {
    mainWindow.loadURL(process.env['ELECTRON_RENDERER_URL'])
  } else {
    mainWindow.loadFile(join(__dirname, '../renderer/index.html'))
  }
}

// SNSなどでのブロックを防ぐため、Chromeの標準的なUser-Agentを偽装
app.userAgentFallback = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'

app.whenReady().then(() => {
  electronApp.setAppUserModelId('com.recipe-shelf')

  // Protocol to serve local images
  protocol.handle('recipe-image', (request) => {
    const url = request.url.replace('recipe-image://', '')
    const filePath = join(imagesPath, url)
    return net.fetch(pathToFileURL(filePath).toString())
  })

  app.on('browser-window-created', (_, window) => {
    optimizer.watchWindowShortcuts(window)
  })

  // IPC Handlers
  ipcMain.handle('get-all-recipes', () => cloudDbService.getAllRecipes())
  
  ipcMain.handle('get-recipe', (_, id: number) => cloudDbService.getRecipeById(id))

  // Supabase Auth Handlers
  ipcMain.handle('supa-login', (_, email, pw) => authService.signIn(email, pw))
  ipcMain.handle('supa-signup', (_, email, pw) => authService.signUp(email, pw))
  ipcMain.handle('supa-logout', () => authService.signOut())
  ipcMain.handle('supa-get-session', () => authService.getSession())
  
  // Migration Task (Local to Cloud)
  ipcMain.handle('migrate-recipes', async () => {
    const session = await authService.getSession()
    if (!session) return false
    
    const localRecipes = dbService.getAllRecipes()
    let migratedCount = 0
    for (const r of localRecipes) {
      if (r.title.includes('(Migrated)')) continue
      try {
        let newImageLocalPath = r.imageLocalPath
        // upload local images to storage
        if (r.imageLocalPath && !r.imageLocalPath.startsWith('http')) {
          const localPath = join(imagesPath, r.imageLocalPath)
          const publicUrl = await storageService.uploadImage(localPath, r.imageLocalPath)
          newImageLocalPath = publicUrl
        }
        await cloudDbService.addRecipe({ ...r, imageLocalPath: newImageLocalPath })
        // mark local as migrated to prevent duplicate migrations
        dbService.updateRecipe(r.id!, { title: r.title + ' (Migrated)' })
        migratedCount++
      } catch (e) {
        console.error('Migration failed for recipe', r.id, e)
      }
    }
    return migratedCount
  })
  
  let loginWindow: BrowserWindow | null = null
  ipcMain.handle('open-login-window', (_, url: string) => {
    if (loginWindow) {
      loginWindow.focus()
      return
    }

    loginWindow = new BrowserWindow({
      width: 800,
      height: 800,
      webPreferences: {
        partition: 'persist:sns',
        sandbox: false
      }
    })

    loginWindow.loadURL(url)

    loginWindow.on('closed', () => {
      loginWindow = null
    })
  })

  ipcMain.handle('add-recipe-from-url', async (_, url: string) => {
    // 1. URLの種類に応じて取得方法を変える (Instagram, TikTok, Facebook, X/Twitter)
    const isSNS = url.includes('instagram.com') || 
                  url.includes('tiktok.com') || 
                  url.includes('facebook.com') || 
                  url.includes('twitter.com') || 
                  url.includes('x.com')
                  
    let scrapedData: Partial<Recipe>

    if (isSNS) {
      // API Key check early
      const apiKey = dbService.getSetting('gemini_api_key')
      if (!apiKey) throw new Error('API Key is not configured for SNS extraction')

      // 隠しウィンドウでレンダリングしてテキストをぶっこ抜く
      scrapedData = await extractWithBrowserWindow(url, apiKey)
    } else {
      // 従来の構造化データスクレイピング
      scrapedData = await scraperService.scrapeUrl(url)
    }
    if (scrapedData.imageUrl) {
      const fileName = await scraperService.downloadImage(scrapedData.imageUrl)
      if (fileName) {
        const localPath = join(imagesPath, fileName)
        const publicUrl = await storageService.uploadImage(localPath, fileName)
        scrapedData.imageLocalPath = publicUrl
      }
    }
    const id = await cloudDbService.addRecipe(scrapedData as Recipe)
    return cloudDbService.getRecipeById(Number(id))
  })

  ipcMain.handle('add-recipe-manual', async (_, recipe: Recipe) => {
    const id = await cloudDbService.addRecipe(recipe)
    return cloudDbService.getRecipeById(Number(id))
  })

  ipcMain.handle('update-recipe', async (_, id: number, recipe: Partial<Recipe>) => {
    await cloudDbService.updateRecipe(id, recipe)
    return cloudDbService.getRecipeById(id)
  })

  ipcMain.handle('delete-recipe', async (_, id: number) => {
    await cloudDbService.deleteRecipe(id)
  })

  ipcMain.handle('reorder-recipes', async (_, orderedIds: number[]) => {
    await cloudDbService.reorderRecipes(orderedIds)
  })

  ipcMain.handle('get-setting', (_, key: string) => {
    return dbService.getSetting(key)
  })

  ipcMain.handle('save-setting', (_, key: string, value: string) => {
    dbService.setSetting(key, value)
  })

  ipcMain.handle('add-recipe-from-text', async (_, text: string, dataUrl?: string) => {
    const apiKey = dbService.getSetting('gemini_api_key')
    if (!apiKey) throw new Error('API Key is not configured')

    // AI抽出
    const recipeData = await aiService.extractRecipeFromText(text, apiKey)

    // クリップボード等から渡されたData URL形式の画像をローカルに保存
    if (dataUrl && dataUrl.startsWith('data:image/')) {
      try {
        const matches = dataUrl.match(/^data:([A-Za-z-+\/]+);base64,(.+)$/)
        if (matches && matches.length === 3) {
          const extension = matches[1].split('/')[1] === 'jpeg' ? 'jpg' : matches[1].split('/')[1]
          const fileName = `${uuidv4()}.${extension}`
          const localPath = join(imagesPath, fileName)
          const buffer = Buffer.from(matches[2], 'base64')
          writeFileSync(localPath, buffer)
          const publicUrl = await storageService.uploadImage(localPath, fileName)
          recipeData.imageLocalPath = publicUrl
        }
      } catch (err) {
        console.error('Failed to save base64 image', err)
      }
    }

    const id = await cloudDbService.addRecipe(recipeData as Recipe)
    return cloudDbService.getRecipeById(Number(id))
  })

  createWindow()

  app.on('activate', function () {
    if (BrowserWindow.getAllWindows().length === 0) createWindow()
  })
})

// SNSなどの動的ページを隠しウィンドウで読み込み、DOMテキストを抽出する関数
async function extractWithBrowserWindow(url: string, apiKey: string): Promise<Partial<Recipe>> {
  return new Promise((resolve, reject) => {
    const hiddenWindow = new BrowserWindow({
      width: 1024,
      height: 768,
      show: true, 
      opacity: 0, // 画面上は見えないようにする
      skipTaskbar: true, // タスクバー/Dockに出さない
      webPreferences: {
        partition: 'persist:sns', // ログインセッションを共有
        sandbox: false,
        backgroundThrottling: false // 裏側でもタイマーや描写を止めない
      }
    })

    hiddenWindow.loadURL(url)

    let timeout = setTimeout(() => {
      hiddenWindow.destroy()
      reject(new Error('Timeout loading page'))
    }, 15000)

    hiddenWindow.webContents.on('did-finish-load', async () => {
      clearTimeout(timeout)
      
      try {
        // DOMのロード状況（中身のテキストが十分に描画されるまで）をポーリングで最大10秒待機する
        await hiddenWindow.webContents.executeJavaScript(`
          return new Promise((resolve) => {
            let attempts = 0;
            const timer = setInterval(() => {
              attempts++;
              
              // 少しずつスクロールして遅延読み込みを促す
              window.scrollBy(0, 500);

              // 続きを読むボタンがあれば随時押す (FacebookやTwitter等の「もっと見る」にも対応)
              const btns = Array.from(document.querySelectorAll('span, div, button, a'));
              for (const btn of btns) {
                const t = btn.textContent ? btn.textContent.trim() : '';
                if (!t || t.length > 20) continue; // テキストが長すぎる要素は無視
                
                const matchKeywords = ['続きを読む', 'more', '続きを見る', 'もっと見る', 'see more', 'さらに表示', '...more', '… さらに表示'];
                const isMatch = matchKeywords.some(kw => t.toLowerCase().includes(kw));
                if (isMatch) {
                  try { btn.click(); } catch (e) {}
                }
              }

              // テキスト量が一定以上（150文字以上）になるか、10秒経過したら完了
              const textLength = document.body.innerText.length;
              if (textLength > 150 || attempts > 20) {
                clearInterval(timer);
                window.scrollTo(0, 0); // 最後に一番上に戻しておく
                resolve(true);
              }
            }, 500);
          });
        `).catch(() => {});
        
        // 更なる非同期レンダリングを少しだけ待つ
        await new Promise(r => setTimeout(r, 1500))

        // ページ内の表示テキストと画像を抽出
        const pageData = await hiddenWindow.webContents.executeJavaScript(`
          (() => {
            const title = document.title || '';
            const ogTitle = document.querySelector('meta[property="og:title"]')?.content || '';
            const ogDesc = document.querySelector('meta[property="og:description"]')?.content || '';
            
            // 複雑なDOM構造に依存せず、画面上のすべての表示テキストを丸ごとGeminiに渡して判別させる
            let bodyText = document.body.innerText;
            if (!bodyText || bodyText.length < 50) {
               bodyText = document.body.textContent || '';
            }

            // 画像のフォールバック (OGPが無い場合、ページ内で「最も面積が大きい」画像を探す)
            let imgUrl = document.querySelector('meta[property="og:image"]')?.content || '';
            if (!imgUrl || imgUrl.includes('facebook.com/images') || imgUrl.includes('fbcdn.net/images')) {
                // Videoのポスター画像があれば優先的に取得
                const videoWithPoster = document.querySelector('video[poster]');
                if (videoWithPoster && videoWithPoster.getAttribute('poster')) {
                    imgUrl = videoWithPoster.getAttribute('poster') || '';
                } else {
                    const imgs = Array.from(document.querySelectorAll('img'));
                    let maxArea = 0;
                    let bestSrc = '';
                    
                    for(const img of imgs) {
                        const w = img.clientWidth || img.width || 0;
                        const h = img.clientHeight || img.height || 0;
                        const area = Number(w) * Number(h);
                        
                        if (area > maxArea && img.src && !img.src.startsWith('data:')) {
                            maxArea = area;
                            bestSrc = img.src;
                        }
                    }
                    
                    if (bestSrc) {
                        imgUrl = bestSrc;
                    }
                }
            }

            const combinedText = [
                "Page Title: " + title,
                "OG Title: " + ogTitle,
                "OG Description: " + ogDesc,
                "--- Body Text ---",
                bodyText
            ].join('\\n');

            return {
              text: combinedText,
              imageUrl: imgUrl
            };
          })()
        `)

        hiddenWindow.destroy()

        // デバッグ用に抽出したテキストを保存
        try {
          writeFileSync('debug_extracted.txt', pageData.text)
        } catch (e) {
          console.error("Failed to write debug file", e)
        }

        // 抽出した生テキストをGeminiに渡す
        const recipeData = await aiService.extractRecipeFromText(pageData.text, apiKey)
        if (pageData.imageUrl) {
          recipeData.imageUrl = pageData.imageUrl
        }
        resolve(recipeData)
      } catch (err) {
        hiddenWindow.destroy()
        reject(err)
      }
    })
  })
}

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit()
  }
})
