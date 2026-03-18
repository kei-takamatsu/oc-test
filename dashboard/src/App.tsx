import { useState, useEffect } from 'react';
import './App.css';

interface Task {
  id: number;
  description: string;
  status: string;
  created_at: string;
}

interface Instruction {
  id: number;
  content: string;
  created_at: string;
}

function App() {
  const [tasks, setTasks] = useState<Task[]>([]);
  const [instructions, setInstructions] = useState<Instruction[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [tasksRes, instRes] = await Promise.all([
          fetch('http://localhost:3001/api/tasks'),
          fetch('http://localhost:3001/api/instructions'),
        ]);

        if (tasksRes.ok) setTasks(await tasksRes.json());
        if (instRes.ok) setInstructions(await instRes.json());
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
    // Refresh every 5 seconds
    const intervalId = setInterval(fetchData, 5000);
    return () => clearInterval(intervalId);
  }, []);

  return (
    <div className="dashboard-container">
      <header className="header">
        <h1>Antigravity Dashboard</h1>
        <div className="status-badge">🟢 Online</div>
      </header>

      <main className="content">
        {loading ? (
          <div className="loading">Data loading...</div>
        ) : (
          <div className="grid">
            <section className="card">
              <h2>Recent Instructions</h2>
              <div className="list">
                {instructions.map((inst) => (
                  <div key={inst.id} className="list-item">
                    <span className="time">{new Date(inst.created_at).toLocaleString('ja-JP')}</span>
                    <p className="description">{inst.content}</p>
                  </div>
                ))}
                {instructions.length === 0 && <p className="empty">No instructions found.</p>}
              </div>
            </section>

            <section className="card">
              <h2>Task Progress</h2>
              <div className="list">
                {tasks.map((task) => (
                  <div key={task.id} className="list-item task-item">
                    <div className="task-header">
                      <span className={`status-icon status-${task.status.replace('_', '-')}`}></span>
                      <p className="description">{task.description}</p>
                    </div>
                    <span className="time">{new Date(task.created_at).toLocaleString('ja-JP')}</span>
                  </div>
                ))}
                {tasks.length === 0 && <p className="empty">No tasks found.</p>}
              </div>
            </section>
          </div>
        )}
      </main>
    </div>
  );
}

export default App;
