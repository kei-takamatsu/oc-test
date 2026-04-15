"use client";

import { useState } from "react";
import { Plus, Trash2, ArrowRight, Rocket } from "lucide-react";
import { createProjectAction } from "@/lib/actions/project-action";

interface CreateProjectFormProps {
  categories: { id: number; name: string }[];
  areas: { id: number; name: string }[];
}

export default function CreateProjectForm({ categories, areas }: CreateProjectFormProps) {
  const [backingLevels, setBackingLevels] = useState([
    { name: "", investAmount: "", returnAmount: "" }
  ]);

  const addLevel = () => {
    setBackingLevels([...backingLevels, { name: "", investAmount: "", returnAmount: "" }]);
  };

  const removeLevel = (index: number) => {
    if (backingLevels.length > 1) {
      setBackingLevels(backingLevels.filter((_, i) => i !== index));
    }
  };

  const updateLevel = (index: number, field: string, value: string) => {
    const newLevels = [...backingLevels];
    (newLevels[index] as any)[field] = value;
    setBackingLevels(newLevels);
  };

  return (
    <form action={async (formData) => {
      formData.append("backingLevelsJson", JSON.stringify(backingLevels));
      await createProjectAction(formData);
    }} className="space-y-12">
      
      {/* 基本情報 */}
      <section className="glass p-8 md:p-12 rounded-[2.5rem] shadow-2xl space-y-8">
        <h2 className="text-2xl font-bold flex items-center gap-3">
          <span className="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm">1</span>
          基本情報を入力
        </h2>
        
        <div className="space-y-6">
          <div className="space-y-2">
            <label className="text-sm font-bold ml-1">プロジェクト名</label>
            <input
              name="projectName"
              type="text"
              required
              placeholder="例: 次世代のスマートデバイスを開発したい"
              className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all text-lg"
            />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div className="space-y-2">
              <label className="text-sm font-bold ml-1">カテゴリ</label>
              <select
                name="categoryId"
                required
                className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all appearance-none"
              >
                <option value="">カテゴリを選択</option>
                {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
              </select>
            </div>
            <div className="space-y-2">
              <label className="text-sm font-bold ml-1">地域（エリア）</label>
              <select
                name="areaId"
                required
                className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all appearance-none"
              >
                <option value="">エリアを選択</option>
                {areas.map(a => <option key={a.id} value={a.id}>{a.name}</option>)}
              </select>
            </div>
          </div>

          <div className="space-y-2">
            <label className="text-sm font-bold ml-1">画像URL</label>
            <input
              name="imageUrl"
              type="url"
              placeholder="https://images.unsplash.com/..."
              className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
            />
          </div>

          <div className="space-y-2">
            <label className="text-sm font-bold ml-1">プロジェクトの概要</label>
            <textarea
              name="description"
              required
              rows={5}
              placeholder="このプロジェクトの目的や、解決したい課題を教えてください。"
              className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all resize-none"
            />
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div className="space-y-2">
              <label className="text-sm font-bold ml-1">目標金額 (円)</label>
              <div className="relative">
                <span className="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-slate-500">¥</span>
                <input
                  name="goalAmount"
                  type="number"
                  required
                  min={1000}
                  placeholder="1,000,000"
                  className="w-full pl-10 pr-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
                />
              </div>
            </div>

            <div className="space-y-2">
              <label className="text-sm font-bold ml-1">募集期間 (日)</label>
              <select
                name="collectionTerm"
                className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all appearance-none"
              >
                <option value="30">30日間</option>
                <option value="45">45日間</option>
                <option value="60" selected>60日間</option>
                <option value="90">90日間</option>
              </select>
            </div>
          </div>
        </div>
      </section>

      {/* リターン（支援レベル） */}
      <section className="glass p-8 md:p-12 rounded-[2.5rem] shadow-2xl space-y-8">
        <div className="flex justify-between items-center">
          <h2 className="text-2xl font-bold flex items-center gap-3">
            <span className="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm">2</span>
            リターン（支援コース）の設定
          </h2>
          <button
            type="button"
            onClick={addLevel}
            className="flex items-center gap-2 px-4 py-2 bg-indigo-500/10 text-indigo-500 rounded-full text-sm font-bold hover:bg-indigo-500/20 transition-colors"
          >
            <Plus size={16} /> コースを追加
          </button>
        </div>

        <div className="space-y-6">
          {backingLevels.map((level, index) => (
            <div key={index} className="p-6 rounded-3xl bg-white/5 border border-white/10 relative space-y-4 group">
              {backingLevels.length > 1 && (
                <button
                  type="button"
                  onClick={() => removeLevel(index)}
                  className="absolute top-4 right-4 text-slate-500 hover:text-red-500 transition-colors"
                >
                  <Trash2 size={20} />
                </button>
              )}
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <label className="text-xs font-bold uppercase tracking-wider text-slate-500">コース名</label>
                  <input
                    value={level.name}
                    onChange={(e) => updateLevel(index, "name", e.target.value)}
                    placeholder="例: ベーシック支援"
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-xs font-bold uppercase tracking-wider text-slate-500">支援金額 (円)</label>
                  <input
                    type="number"
                    value={level.investAmount}
                    onChange={(e) => updateLevel(index, "investAmount", e.target.value)}
                    placeholder="3,000"
                    className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:outline-none focus:ring-2 focus:ring-primary"
                    required
                  />
                </div>
              </div>
              
              <div className="space-y-2">
                <label className="text-xs font-bold uppercase tracking-wider text-slate-500">リターンの内容</label>
                <textarea
                  value={level.returnAmount}
                  onChange={(e) => updateLevel(index, "returnAmount", e.target.value)}
                  placeholder="支援者に提供するモノやサービスの内容を詳しく入力してください。"
                  className="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                  rows={3}
                  required
                />
              </div>
            </div>
          ))}
        </div>
      </section>

      <div className="pt-6">
        <button
          type="submit"
          className="w-full py-5 bg-primary text-white font-bold rounded-[2rem] shadow-2xl shadow-indigo-500/20 hover-subtle text-xl flex items-center justify-center gap-3"
        >
          プロジェクトを公開する <ArrowRight size={24} />
        </button>
        <p className="text-center text-slate-500 text-sm mt-6">
          公開後でも一部の情報を除き編集可能です。
        </p>
      </div>
    </form>
  );
}
