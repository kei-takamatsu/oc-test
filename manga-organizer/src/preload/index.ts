import { contextBridge } from 'electron'
import { electronAPI } from '@electron-toolkit/preload'

// Custom APIs for renderer
const api = {
  selectFile: () => electronAPI.ipcRenderer.invoke('select-file'),
  processArchive: (filePath: string, options: any) => electronAPI.ipcRenderer.invoke('process-archive', filePath, options),
  onProgress: (callback: (status: any) => void) => {
    const listener = (_event: any, status: any) => callback(status)
    electronAPI.ipcRenderer.on('process-progress', listener)
    return () => electronAPI.ipcRenderer.removeListener('process-progress', listener)
  }
}

// Use `contextBridge` APIs to expose Electron APIs to
// renderer only if context isolation is enabled, otherwise
// just add to the DOM global.
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
