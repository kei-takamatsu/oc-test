import { useState, useEffect, useCallback } from 'react'

interface ProcessStatus {
  total: number
  current: number
  message: string
}

function App(): React.JSX.Element {
  const [filePath, setFilePath] = useState<string | null>(null)
  const [isProcessing, setIsProcessing] = useState(false)
  const [status, setStatus] = useState<ProcessStatus | null>(null)
  const [sensitivity, setSensitivity] = useState(10)
  const [enableScaleTrim, setEnableScaleTrim] = useState(true)
  const [splitSpreads, setSplitSpreads] = useState(true)

  useEffect(() => {
    const removeListener = window.api.onProgress((newStatus: ProcessStatus) => {
      setStatus(newStatus)
    })
    return () => removeListener()
  }, [])

  const handleSelectFile = async () => {
    const path = await window.api.selectFile()
    if (path) setFilePath(path)
  }

  const handleStart = async () => {
    if (!filePath) return
    setIsProcessing(true)
    try {
      await window.api.processArchive(filePath, {
        trimSensitivity: sensitivity,
        enableScaleTrimming: enableScaleTrim,
        splitSpreads: splitSpreads
      })
      alert('処理が完了しました！')
    } catch (err: any) {
      alert(`エラーが発生しました: ${err.message}`)
    } finally {
      setIsProcessing(false)
      setStatus(null)
    }
  }

  const onDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault()
    const file = e.dataTransfer.files[0] as any
    if (file && (file.path.endsWith('.zip') || file.path.endsWith('.rar') || file.path.endsWith('.cbz') || file.path.endsWith('.cbr'))) {
      setFilePath(file.path)
    }
  }, [])

  return (
    <div className="container" onDragOver={(e) => e.preventDefault()} onDrop={onDrop}>
      <header>
        <h1>Manga Organizer</h1>
        <p>アーカイブをドラッグ＆ドロップして整理を開始</p>
      </header>

      <main className="glass-panel">
        {!isProcessing ? (
          <div className="setup-view">
            <div className={`drop-zone ${filePath ? 'has-file' : ''}`} onClick={handleSelectFile}>
              {filePath ? (
                <div className="file-info">
                  <span className="icon">📦</span>
                  <span className="filename">{filePath.split(/[\\/]/).pop()}</span>
                </div>
              ) : (
                <div className="prompt">
                  <span className="icon">📂</span>
                  <span>クリックして選択、またはファイルをドロップ</span>
                </div>
              )}
            </div>

            <div className="settings">
              <div className="setting-item">
                <label>
                  トリミング感度: {sensitivity}
                  <input
                    type="range"
                    min="0"
                    max="50"
                    value={sensitivity}
                    onChange={(e) => setSensitivity(parseInt(e.target.value))}
                  />
                </label>
              </div>
              <div className="setting-item">
                <label className="checkbox-label">
                  <input
                    type="checkbox"
                    checked={enableScaleTrim}
                    onChange={(e) => setEnableScaleTrim(e.target.checked)}
                  />
                  <span>スケール認識トリミング</span>
                </label>
              </div>
              <div className="setting-item">
                <label className="checkbox-label">
                  <input
                    type="checkbox"
                    checked={splitSpreads}
                    onChange={(e) => setSplitSpreads(e.target.checked)}
                  />
                  <span>見開きを2ページに分割</span>
                </label>
              </div>
            </div>

            <button className="start-button" onClick={handleStart} disabled={!filePath}>
              整理を開始する
            </button>
          </div>
        ) : (
          <div className="progress-view">
            <div className="status-message">{status?.message || '準備中...'}</div>
            <div className="progress-bar-container">
              <div 
                className="progress-bar-fill" 
                style={{ width: `${status ? (status.current / status.total) * 100 : 0}%` }}
              ></div>
            </div>
            <div className="progress-stats">
              {status ? `${status.current} / ${status.total}` : ''}
            </div>
          </div>
        )}
      </main>

      <footer>
        <p>© 2026 Manga Organizer Pro</p>
      </footer>
    </div>
  )
}

export default App
