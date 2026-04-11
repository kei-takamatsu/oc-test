/// <reference types="vite/client" />

interface Window {
  electron: import('@electron-toolkit/preload').ElectronAPI;
  api: {
    selectFile: () => Promise<string | undefined>;
    processArchive: (filePath: string, options: any) => Promise<string>;
    onProgress: (callback: (status: any) => void) => () => void;
  };
}
