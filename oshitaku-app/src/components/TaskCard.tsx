import React from 'react';
import { motion } from 'framer-motion';
import { CheckCircle2, Circle, Zap } from 'lucide-react';
import type { Task } from '../lib/constants';

interface TaskCardProps {
  task: Task;
  onToggle: (id: string) => void;
  isMorning: boolean;
}

export const TaskCard: React.FC<TaskCardProps> = ({ task, onToggle, isMorning }) => {
  return (
    <motion.div
      layout
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, scale: 0.95 }}
      whileTap={{ scale: 0.98 }}
      onClick={() => onToggle(task.id)}
      className={`
        relative group cursor-pointer p-5 rounded-3xl flex items-center justify-between
        transition-all duration-300 border-2
        ${task.done 
          ? 'bg-white/40 border-transparent text-slate-400' 
          : isMorning 
            ? 'bg-morning/10 border-morning/20 text-morning-foreground hover:shadow-lg hover:shadow-morning/10' 
            : 'bg-evening/10 border-evening/20 text-evening-foreground hover:shadow-lg hover:shadow-evening/10'}
        ${!task.done && 'glass-card'}
      `}
    >
      <div className="flex items-center gap-4">
        <span className="text-4xl filter drop-shadow-sm">{task.icon}</span>
        <span className={`text-xl font-bold ${task.done ? 'line-through opacity-50' : ''}`}>
          {task.text}
        </span>
      </div>
      
      <motion.div
        animate={{ scale: task.done ? [1, 1.2, 1] : 1 }}
        transition={{ duration: 0.3 }}
      >
        {task.done ? (
          <CheckCircle2 className={`w-10 h-10 ${isMorning ? 'text-morning' : 'text-evening'}`} />
        ) : (
          <Circle className="w-10 h-10 text-slate-200" />
        )}
      </motion.div>
      
      {task.done && (
        <motion.div
          initial={{ scale: 0 }}
          animate={{ scale: 1 }}
          className="absolute -top-2 -right-2 bg-green-400 text-white p-1 rounded-full shadow-lg"
        >
          <Zap className="w-4 h-4 fill-current" />
        </motion.div>
      )}
    </motion.div>
  );
};
