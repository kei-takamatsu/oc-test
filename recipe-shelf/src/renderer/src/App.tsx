import React, { useState, useEffect } from 'react'
import { 
  Plus, 
  ChefHat, 
  BookOpen, 
  Settings, 
  Clock, 
  Users, 
  ArrowLeft, 
  Trash2, 
  ExternalLink,
  Globe,
  Loader2
} from 'lucide-react'
import { motion, AnimatePresence } from 'framer-motion'

export default function App() {
  const [session, setSession] = useState<any>(null)
  const [authMode, setAuthMode] = useState<'login' | 'signup'>('login')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [authLoading, setAuthLoading] = useState(true)

  const [recipes, setRecipes] = useState<Recipe[]>([])
  const [selectedRecipe, setSelectedRecipe] = useState<Recipe | null>(null)
  const [view, setView] = useState<'shelf' | 'detail' | 'settings'>('shelf')
  const [isAddModalOpen, setIsAddModalOpen] = useState(false)

  // Add Recipe State
  const [addTab, setAddTab] = useState<'url' | 'text'>('url')
  const [importUrl, setImportUrl] = useState('')
  const [importText, setImportText] = useState('')
  const [importImageBase64, setImportImageBase64] = useState<string | null>(null)
  const [isImporting, setIsImporting] = useState(false)

  // Settings State
  const [apiKey, setApiKey] = useState('')

  useEffect(() => {
    const init = async () => {
      const s = await window.api.supaGetSession()
      setSession(s)
      if (s) {
        window.api.migrateRecipes().then(count => {
          if (count && count > 0) {
            alert(`${count}件のローカルレシピをクラウドに移行しました！`)
            loadRecipes()
          }
        })
        loadRecipes()
      }
      setAuthLoading(false)
      loadSettings()
    }
    init()
  }, [])

  const handleAuth = async () => {
    try {
      setAuthLoading(true)
      let data
      if (authMode === 'login') {
        data = await window.api.supaLogin(email, password)
      } else {
        data = await window.api.supaSignup(email, password)
        alert('登録が完了しました。一度ログインをお試しください。')
      }
      
      if (data?.session) {
        setSession(data.session)
        window.api.migrateRecipes().then(count => {
          if (count && count > 0) alert(`${count}件のレシピをクラウドへ移行しました！`)
          loadRecipes()
        })
        loadRecipes()
      }
    } catch (error: any) {
      alert('認証エラー: ' + error.message)
    } finally {
      setAuthLoading(false)
    }
  }

  const handleLogout = async () => {
    await window.api.supaLogout()
    setSession(null)
    setRecipes([])
  }

  const getImageUrl = (path?: string) => {
    if (!path) return 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=800&q=80';
    if (path.startsWith('http')) return path;
    return `recipe-image://${path}`;
  }


  const loadSettings = async () => {
    const savedKey = await window.api.getSetting('gemini_api_key')
    if (savedKey) setApiKey(savedKey)
  }

  const handleSaveSettings = async () => {
    await window.api.saveSetting('gemini_api_key', apiKey)
    alert('設定を保存しました。')
  }

  const loadRecipes = async () => {
    const data = await window.api.getAllRecipes()
    setRecipes(data)
  }

  const handleImport = async () => {
    if (!importUrl) return
    setIsImporting(true)
    try {
      const newRecipe = await window.api.addRecipeFromUrl(importUrl)
      if (newRecipe && newRecipe.title !== 'Unknown Recipe') {
        setRecipes([newRecipe, ...recipes])
        setIsAddModalOpen(false)
        setImportUrl('')
      } else {
        alert('レシピ情報の取得に失敗しました。対応していないサイトの可能性があります。')
      }
    } catch (error) {
      alert('インポートに失敗しました。URLが正しいか確認してください。')
    } finally {
      setIsImporting(false)
    }
  }

  const handleImportText = async () => {
    if (!importText) return
    setIsImporting(true)
    try {
      const newRecipe = await window.api.addRecipeFromText(importText, importImageBase64 || undefined)
      setRecipes([newRecipe, ...recipes])
      setIsAddModalOpen(false)
      setImportText('')
      setImportImageBase64(null)
    } catch (error: any) {
      alert(`AIでの抽出に失敗しました: ${error.message}`)
    } finally {
      setIsImporting(false)
    }
  }

  const handlePasteImage = (e: React.ClipboardEvent) => {
    const items = e.clipboardData.items
    for (let i = 0; i < items.length; i++) {
      if (items[i].type.indexOf('image') !== -1) {
        const blob = items[i].getAsFile()
        if (blob) {
          const reader = new FileReader()
          reader.onload = (event) => {
            if (event.target && typeof event.target.result === 'string') {
              setImportImageBase64(event.target.result)
            }
          }
          reader.readAsDataURL(blob)
        }
        break
      }
    }
  }

  const handleDelete = async (id: number) => {
    if (confirm('このレシピを削除してもよろしいですか？')) {
      await window.api.deleteRecipe(id)
      setRecipes(recipes.filter(r => r.id !== id))
      setView('shelf')
      setSelectedRecipe(null)
    }
  }

  const openRecipe = (recipe: Recipe) => {
    setSelectedRecipe(recipe)
    setView('detail')
  }

  if (authLoading) {
    return <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh', background: 'var(--bg-primary)' }}>
      <Loader2 className="loading-spinner" size={48} />
    </div>
  }

  if (!session) {
    return (
      <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', height: '100vh', background: 'var(--bg-primary)' }}>
        <div style={{ background: 'var(--bg-secondary)', padding: '40px', borderRadius: '16px', width: '400px', boxShadow: '0 10px 30px rgba(0,0,0,0.5)' }}>
          <div style={{ textAlign: 'center', marginBottom: '30px' }}>
             <ChefHat size={48} color="#f39c12" style={{ marginBottom: '10px' }} />
             <h2>RecipeShelf</h2>
             <p style={{ color: 'var(--text-secondary)', marginTop: '8px' }}>クラウドへレシピを保存しましょう</p>
          </div>
          
          <div className="input-group">
            <label className="input-label">メールアドレス</label>
            <input type="email" value={email} onChange={e=>setEmail(e.target.value)} className="input-field" placeholder="example@email.com" />
          </div>
          <div className="input-group">
            <label className="input-label">パスワード</label>
            <input type="password" value={password} onChange={e=>setPassword(e.target.value)} className="input-field" placeholder="••••••••" />
          </div>
          
          <button className="btn btn-primary" style={{ width: '100%', marginTop: '20px', justifyContent: 'center' }} onClick={handleAuth}>
             {authMode === 'login' ? 'ログイン' : '新規登録'}
          </button>
          
          <div style={{ textAlign: 'center', marginTop: '20px' }}>
            <span style={{ color: 'var(--text-secondary)', cursor: 'pointer', fontSize: '0.9rem', textDecoration: 'underline' }} onClick={() => setAuthMode(authMode === 'login' ? 'signup' : 'login')}>
              {authMode === 'login' ? 'アカウントをお持ちでない方は新規登録' : '既にアカウントをお持ちの方はログイン'}
            </span>
          </div>
        </div>
      </div>
    )
  }

  return (
    <>
      <div className="sidebar">
        <div className="sidebar-title">
          <ChefHat size={32} color="#f39c12" />
          <span>RecipeShelf</span>
        </div>

        <div className={`nav-item ${view === 'shelf' ? 'active' : ''}`} onClick={() => setView('shelf')}>
          <BookOpen size={20} />
          <span>マイレシピ</span>
        </div>

        <div className={`nav-item ${view === 'settings' ? 'active' : ''}`} onClick={() => setView('settings')}>
          <Settings size={20} />
          <span>設定</span>
        </div>
      </div>

      <main className="main-container">
        <AnimatePresence mode="wait">
          {view === 'shelf' ? (
            <motion.div 
              key="shelf"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -20 }}
            >
              <div className="header">
                <h1>保存済みレシピ</h1>
                <button className="btn btn-primary" onClick={() => setIsAddModalOpen(true)}>
                  <Plus size={20} />
                  レシピを追加
                </button>
              </div>

              <div className="recipe-grid">
                {recipes.map(recipe => (
                  <div key={recipe.id} className="recipe-card" onClick={() => openRecipe(recipe)}>
                    <img 
                      className="recipe-card-image" 
                      src={getImageUrl(recipe.imageLocalPath)} 
                      alt={recipe.title} 
                    />
                    <div className="recipe-card-content">
                      <h3 className="recipe-card-title">{recipe.title}</h3>
                      <div className="recipe-card-meta">
                        {recipe.cookTime && <span><Clock size={14} /> 調理: {recipe.cookTime}</span>}
                        {recipe.servings && <span><Users size={14} /> {recipe.servings}</span>}
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {recipes.length === 0 && (
                <div style={{ textAlign: 'center', marginTop: '100px', color: 'var(--text-secondary)' }}>
                  <Globe size={48} style={{ marginBottom: '20px', opacity: 0.5 }} />
                  <p>まだレシピがありません。「レシピを追加」からURLでインポートしてください！</p>
                </div>
              )}
            </motion.div>
          ) : view === 'settings' ? (
            <motion.div
              key="settings"
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -20 }}
            >
               <div className="header">
                <h1>設定</h1>
                <button className="btn" style={{ background: 'transparent', color: 'var(--text-secondary)' }} onClick={handleLogout}>
                  ログアウト
                </button>
              </div>

              <div className="recipe-section" style={{ maxWidth: '600px' }}>
                <h2>Gemini API キー (AIテキスト抽出用)</h2>
                <p style={{ color: 'var(--text-secondary)', marginBottom: '16px', lineHeight: '1.5' }}>
                  Instagram等のSNSの投稿文からレシピを自動抽出するために、Google GeminiのAPIを使用します。
                  <a href="https://aistudio.google.com/app/apikey" target="_blank" rel="noreferrer" style={{ color: 'var(--accent-color)' }}>Google AI Studio</a>で取得したキーを入力してください。
                </p>
                
                <div className="input-group">
                  <input 
                    type="password" 
                    className="input-field" 
                    placeholder="AIzaSy..." 
                    value={apiKey}
                    onChange={e => setApiKey(e.target.value)}
                  />
                </div>
                
                <button className="btn btn-primary" onClick={handleSaveSettings}>設定を保存</button>
              </div>

              <div className="recipe-section" style={{ maxWidth: '600px', marginTop: '40px' }}>
                <h2>SNS 自動連動設定</h2>
                <p style={{ color: 'var(--text-secondary)', marginBottom: '16px', lineHeight: '1.5' }}>
                  SNSのアカウントでログインしておくことで、URLから直接レシピを解析できるようになります。
                  ログイン情報はアプリ内にのみ保存されます。
                </p>
                <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap' }}>
                  <button 
                    className="btn" 
                    style={{ background: '#E1306C', color: 'white' }}
                    onClick={() => window.api.openLoginWindow('https://www.instagram.com/accounts/login/')}
                  >
                    Instagram にログイン
                  </button>
                  <button 
                    className="btn" 
                    style={{ background: '#000000', color: 'white', border: '1px solid #333' }}
                    onClick={() => window.api.openLoginWindow('https://www.tiktok.com/login')}
                  >
                    TikTok にログイン
                  </button>
                  <button 
                    className="btn" 
                    style={{ background: '#000000', color: 'white', border: '1px solid #333' }}
                    onClick={() => window.api.openLoginWindow('https://twitter.com/i/flow/login')}
                  >
                    X (Twitter) にログイン
                  </button>
                  <button 
                    className="btn" 
                    style={{ background: '#1877F2', color: 'white' }}
                    onClick={() => window.api.openLoginWindow('https://www.facebook.com/login')}
                  >
                    Facebook にログイン
                  </button>
                </div>
              </div>
            </motion.div>
          ) : (
            <motion.div
              key="detail"
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -20 }}
              className="recipe-detail"
            >
              <button className="nav-item" style={{ marginBottom: '30px', padding: '8px 0' }} onClick={() => setView('shelf')}>
                <ArrowLeft size={20} />
                戻る
              </button>

              <div className="recipe-detail-header">
                <img 
                  className="recipe-detail-image" 
                  src={getImageUrl(selectedRecipe?.imageLocalPath)} 
                  alt={selectedRecipe?.title} 
                />
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start' }}>
                  <h1 className="recipe-detail-title">{selectedRecipe?.title}</h1>
                  <div style={{ display: 'flex', gap: '10px' }}>
                    {selectedRecipe?.sourceUrl && (
                      <button className="btn" style={{ background: '#222', color: 'white' }} onClick={() => window.open(selectedRecipe.sourceUrl)}>
                        <ExternalLink size={20} />
                      </button>
                    )}
                    <button className="btn" style={{ background: 'rgba(231, 76, 60, 0.2)', color: '#e74c3c' }} onClick={() => handleDelete(selectedRecipe!.id!)}>
                      <Trash2 size={20} />
                    </button>
                  </div>
                </div>
                
                <div className="recipe-detail-meta">
                  {selectedRecipe?.prepTime && <span>準備: {selectedRecipe.prepTime}</span>}
                  {selectedRecipe?.cookTime && <span>調理: {selectedRecipe.cookTime}</span>}
                  {selectedRecipe?.servings && <span>分量: {selectedRecipe.servings}</span>}
                </div>

                <p style={{ fontSize: '1.2rem', lineHeight: '1.6', color: 'var(--text-secondary)', marginBottom: '40px' }}>
                  {selectedRecipe?.description}
                </p>
              </div>

              <div className="recipe-section">
                <h2>材料</h2>
                <ul className="ingredient-list">
                  {JSON.parse(selectedRecipe?.ingredients || '[]').map((ing: string, i: number) => (
                    <li key={i} className="ingredient-item">{ing}</li>
                  ))}
                  {JSON.parse(selectedRecipe?.ingredients || '[]').length === 0 && (
                    <li className="ingredient-item" style={{ color: 'var(--text-secondary)' }}>材料情報がありません</li>
                  )}
                </ul>
              </div>

              <div className="recipe-section">
                <h2>作り方</h2>
                <ol className="step-list">
                  {JSON.parse(selectedRecipe?.instructions || '[]').map((step: string, i: number) => (
                    <li key={i} className="step-item">{step}</li>
                  ))}
                  {JSON.parse(selectedRecipe?.instructions || '[]').length === 0 && (
                    <li style={{ color: 'var(--text-secondary)', marginTop: '20px' }}>作り方の情報がありません</li>
                  )}
                </ol>
              </div>
            </motion.div>
          )}
        </AnimatePresence>
      </main>

      <AnimatePresence>
        {isAddModalOpen && (
          <div className="modal-overlay" onClick={() => setIsAddModalOpen(false)}>
            <motion.div 
              className="modal-content"
              initial={{ scale: 0.9, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.9, opacity: 0 }}
              onClick={e => e.stopPropagation()}
            >
              <h2 style={{ marginBottom: '24px' }}>レシピを登録</h2>

              <div className="tabs">
                <div className={`tab ${addTab === 'url' ? 'active' : ''}`} onClick={() => setAddTab('url')}>URLで抽出</div>
                <div className={`tab ${addTab === 'text' ? 'active' : ''}`} onClick={() => setAddTab('text')}>テキスト(SNS)で抽出</div>
              </div>

              {addTab === 'url' ? (
                <>
                  <div className="input-group">
                    <label className="input-label">レシピのURL（クックパッド、クラシルなど）</label>
                    <input 
                      type="text" 
                      className="input-field" 
                      placeholder="https://example.com/recipe/..." 
                      value={importUrl}
                      onChange={e => setImportUrl(e.target.value)}
                      disabled={isImporting}
                    />
                  </div>
                  <div style={{ display: 'flex', gap: '12px', justifyContent: 'flex-end' }}>
                    <button className="btn" style={{ background: 'transparent', color: 'var(--text-secondary)' }} onClick={() => setIsAddModalOpen(false)}>キャンセル</button>
                    <button className="btn btn-primary" onClick={handleImport} disabled={isImporting || !importUrl}>
                      {isImporting ? <Loader2 className="loading-spinner" size={20} /> : 'データを取得'}
                    </button>
                  </div>
                </>
              ) : (
                <>
                  <div className="input-group">
                    <label className="input-label">SNSの投稿文（キャプション）をそのままペースト</label>
                    <textarea 
                      className="textarea-field" 
                      placeholder="例: 今日は美味しいカレーを作りました！ 材料: 玉ねぎ 1個..." 
                      value={importText}
                      onChange={e => setImportText(e.target.value)}
                      disabled={isImporting}
                    />
                  </div>

                  <div className="input-group">
                    <label className="input-label">完成画像（画像をコピーして下の領域でペースト：Cmd+V）</label>
                    <div className="image-drop-area" onPaste={handlePasteImage} tabIndex={0}>
                      {importImageBase64 ? (
                        <>
                          <img src={importImageBase64} alt="Pasted" className="image-preview" />
                          <span style={{ fontSize: '0.8rem' }}>別の画像をペーストして上書き</span>
                        </>
                      ) : (
                        <span>ここをクリックして画像をペースト (Cmd+V)</span>
                      )}
                    </div>
                  </div>

                  <div style={{ display: 'flex', gap: '12px', justifyContent: 'flex-end' }}>
                    <button className="btn" style={{ background: 'transparent', color: 'var(--text-secondary)' }} onClick={() => setIsAddModalOpen(false)}>キャンセル</button>
                    <button className="btn btn-primary" onClick={handleImportText} disabled={isImporting || !importText}>
                      {isImporting ? <Loader2 className="loading-spinner" size={20} /> : 'AIでレシピを抽出'}
                    </button>
                  </div>
                </>
              )}
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </>
  )
}
