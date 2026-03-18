function App() {
  return (
    <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
      <h1>oshitaku-app</h1>
      <p>アプリが正常に動作しています！</p>
      <p>Supabase URL: {import.meta.env.VITE_SUPABASE_URL || 'Not set'}</p>
    </div>
  );
}

export default App;
