"use strict";
const electron = require("electron");
const preload = require("@electron-toolkit/preload");
const api = {
  getAllRecipes: () => preload.electronAPI.ipcRenderer.invoke("get-all-recipes"),
  getRecipe: (id) => preload.electronAPI.ipcRenderer.invoke("get-recipe", id),
  addRecipeFromUrl: (url) => preload.electronAPI.ipcRenderer.invoke("add-recipe-from-url", url),
  addRecipeManual: (recipe) => preload.electronAPI.ipcRenderer.invoke("add-recipe-manual", recipe),
  updateRecipe: (id, recipe) => preload.electronAPI.ipcRenderer.invoke("update-recipe", id, recipe),
  deleteRecipe: (id) => preload.electronAPI.ipcRenderer.invoke("delete-recipe", id),
  getSetting: (key) => preload.electronAPI.ipcRenderer.invoke("get-setting", key),
  saveSetting: (key, value) => preload.electronAPI.ipcRenderer.invoke("save-setting", key, value),
  addRecipeFromText: (text, dataUrl) => preload.electronAPI.ipcRenderer.invoke("add-recipe-from-text", text, dataUrl),
  openLoginWindow: (url) => preload.electronAPI.ipcRenderer.invoke("open-login-window", url),
  supaLogin: (email, pw) => preload.electronAPI.ipcRenderer.invoke("supa-login", email, pw),
  supaSignup: (email, pw) => preload.electronAPI.ipcRenderer.invoke("supa-signup", email, pw),
  supaLogout: () => preload.electronAPI.ipcRenderer.invoke("supa-logout"),
  supaGetSession: () => preload.electronAPI.ipcRenderer.invoke("supa-get-session"),
  migrateRecipes: () => preload.electronAPI.ipcRenderer.invoke("migrate-recipes")
};
if (process.contextIsolated) {
  try {
    electron.contextBridge.exposeInMainWorld("electron", preload.electronAPI);
    electron.contextBridge.exposeInMainWorld("api", api);
  } catch (error) {
    console.error(error);
  }
} else {
  window.electron = preload.electronAPI;
  window.api = api;
}
