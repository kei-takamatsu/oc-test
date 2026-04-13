import { createProject } from "@/lib/actions/project";

export default function CreateProjectPage() {
  return (
    <div className="container mx-auto px-4 py-16">
      <div className="max-w-2xl mx-auto space-y-8">
        <div className="space-y-2">
          <h1 className="text-3xl font-bold tracking-tight">プロジェクトを始める</h1>
          <p className="text-slate-500">あなたのアイデアに、新しい重力を。必要な情報を入力して、夢の第一歩を踏み出しましょう。</p>
        </div>

        <form action={createProject} className="space-y-6 glass p-8 rounded-3xl shadow-2xl">
          <div className="space-y-4">
            <div className="space-y-2">
              <label htmlFor="projectName" className="text-sm font-semibold text-slate-700 dark:text-slate-300">
                プロジェクト名
              </label>
              <input
                id="projectName"
                name="projectName"
                type="text"
                required
                placeholder="例: 次世代のスマートデバイスを開発したい"
                className="w-full px-4 py-3 rounded-xl bg-white/5 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none transition-all"
              />
            </div>

            <div className="space-y-2">
              <label htmlFor="description" className="text-sm font-semibold text-slate-700 dark:text-slate-300">
                プロジェクトの概要
              </label>
              <textarea
                id="description"
                name="description"
                required
                rows={5}
                placeholder="このプロジェクトの目的や、解決したい課題を教えてください。"
                className="w-full px-4 py-3 rounded-xl bg-white/5 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none transition-all resize-none"
              />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="space-y-2">
                <label htmlFor="goalAmount" className="text-sm font-semibold text-slate-700 dark:text-slate-300">
                  目標金額 (円)
                </label>
                <input
                  id="goalAmount"
                  name="goalAmount"
                  type="number"
                  required
                  min={1000}
                  placeholder="100,000"
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none transition-all"
                />
              </div>

              <div className="space-y-2">
                <label htmlFor="collectionTerm" className="text-sm font-semibold text-slate-700 dark:text-slate-300">
                  募集期間 (日)
                </label>
                <select
                  id="collectionTerm"
                  name="collectionTerm"
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-slate-200 dark:border-slate-800 focus:ring-2 focus:ring-primary outline-none transition-all"
                >
                  <option value="30">30日間</option>
                  <option value="45">45日間</option>
                  <option value="60" selected>60日間</option>
                  <option value="90">90日間</option>
                </select>
              </div>
            </div>
          </div>

          <div className="pt-6">
            <button
              type="submit"
              className="w-full py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 hover-subtle"
            >
              プロジェクトを下書き保存する
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
