import { contextBridge } from 'electron'
import { electronAPI } from '@electron-toolkit/preload'

const api = {
  getAllRecipes: () => electronAPI.ipcRenderer.invoke('get-all-recipes'),
  getRecipe: (id: number) => electronAPI.ipcRenderer.invoke('get-recipe', id),
  addRecipeFromUrl: (url: string) => electronAPI.ipcRenderer.invoke('add-recipe-from-url', url),
  addRecipeManual: (recipe: any) => electronAPI.ipcRenderer.invoke('add-recipe-manual', recipe),
  updateRecipe: (id: number, recipe: any) => electronAPI.ipcRenderer.invoke('update-recipe', id, recipe),
  deleteRecipe: (id: number) => electronAPI.ipcRenderer.invoke('delete-recipe', id),
  getSetting: (key: string) => electronAPI.ipcRenderer.invoke('get-setting', key),
  saveSetting: (key: string, value: string) => electronAPI.ipcRenderer.invoke('save-setting', key, value),
  addRecipeFromText: (text: string, dataUrl?: string) => electronAPI.ipcRenderer.invoke('add-recipe-from-text', text, dataUrl),
  openLoginWindow: (url: string) => electronAPI.ipcRenderer.invoke('open-login-window', url),
  supaLogin: (email: string, pw: string) => electronAPI.ipcRenderer.invoke('supa-login', email, pw),
  supaSignup: (email: string, pw: string) => electronAPI.ipcRenderer.invoke('supa-signup', email, pw),
  supaLogout: () => electronAPI.ipcRenderer.invoke('supa-logout'),
  supaGetSession: () => electronAPI.ipcRenderer.invoke('supa-get-session'),
  migrateRecipes: () => electronAPI.ipcRenderer.invoke('migrate-recipes')
}

if (process.contextIsolated) {
  try {
    contextBridge.exposeInMainWorld('electron', electronAPI)
    contextBridge.exposeInMainWorld('api', api)
  } catch (error) {
    console.error(error)
  }
} else {
  // @ts-ignore (define in dts)
  window.electron = electronAPI
  // @ts-ignore (define in dts)
  window.api = api
}
