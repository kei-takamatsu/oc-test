/**
 * Discord Bot Client
 * 役割: Discordからのメッセージを受信し、エージェントのロジックへ橋渡しする
 */

import { Client, GatewayIntentBits, Message, REST, Routes, SlashCommandBuilder } from 'discord.js';
import * as dotenv from 'dotenv';
import { ProjectManager } from '../core/project-manager.ts';

console.log('Starting Discord bot...');
const result = dotenv.config();
if (result.error) {
  console.error('Error loading .env file:', result.error);
} else {
  console.log('.env file loaded successfully.');
}

const client = new Client({
  intents: [
    GatewayIntentBits.Guilds,
    GatewayIntentBits.GuildMessages,
    GatewayIntentBits.MessageContent,
  ],
});

// スラッシュコマンドの定義
const commands = [
  new SlashCommandBuilder().setName('status').setDescription('現在のプロジェクトの進捗を確認します'),
  new SlashCommandBuilder()
    .setName('read')
    .setDescription('プロジェクト内のファイル内容を読み取ります')
    .addStringOption(option => 
      option.setName('path').setDescription('読み取りたいファイルのパス（例: src/utils/discord-client.ts）').setRequired(true)
    ),
  new SlashCommandBuilder()
    .setName('ask')
    .setDescription('開発について相談します')
    .addStringOption(option => 
      option.setName('query').setDescription('相談内容').setRequired(true)
    ),
  new SlashCommandBuilder()
    .setName('instruct')
    .setDescription('開発の指示を直接出します')
    .addStringOption(option => 
      option.setName('instruction').setDescription('指示内容').setRequired(true)
    ),
  new SlashCommandBuilder()
    .setName('push')
    .setDescription('GitHubに現在のコードをプッシュします'),
].map(command => command.toJSON());

// コマンドの登録
const registerCommands = async () => {
  const token = process.env.DISCORD_TOKEN;
  const clientId = process.env.CLIENT_ID;
  const guildId = process.env.GUILD_ID;

  if (!token || !clientId) {
    console.error('DISCORD_TOKEN or CLIENT_ID is missing for command registration');
    return;
  }

  const rest = new REST({ version: '10' }).setToken(token);

  try {
    console.log('Started refreshing application (/) commands.');
    // IDが数値形式（Snowflake）であるか簡易チェック
    const isValidSnowflake = (id: string) => /^\d{17,19}$/.test(id);

    if (guildId && isValidSnowflake(guildId)) {
      console.log(`Registering guild commands for: ${guildId}`);
      await rest.put(Routes.applicationGuildCommands(clientId, guildId), { body: commands });
    } else {
      console.log('Registering global commands.');
      await rest.put(Routes.applicationCommands(clientId), { body: commands });
    }
    console.log('Successfully reloaded application (/) commands.');
  } catch (error) {
    console.error('Error registering commands:', error);
  }
};

client.once('clientReady', (readyClient) => {
  console.log(`Bot is ready! Logged in as ${readyClient.user.tag}`);
  registerCommands();
});

// スラッシュコマンドのインタラクション処理
client.on('interactionCreate', async (interaction) => {
  if (!interaction.isChatInputCommand()) return;

  const { commandName } = interaction;

  if (commandName === 'status') {
    await interaction.deferReply();
    const summary = await ProjectManager.getProgressSummary();
    await interaction.editReply(summary);
  } else if (commandName === 'read') {
    const filePath = interaction.options.getString('path', true);
    await interaction.deferReply();
    const content = await ProjectManager.readFile(filePath);
    await interaction.editReply(`📄 **File: ${filePath}**\n\`\`\`typescript\n${content}\n\`\`\``);
  } else if (commandName === 'ask') {
    const query = interaction.options.getString('query', true);
    await interaction.deferReply();
    // TODO: ここでエージェント（Antigravity）の推論をシミュレート
    await interaction.editReply(`💡 **相談内容:** ${query}\n\nエージェントとしての回答を生成中です... 現在のプロジェクトコンテキスト（task.mdやソースコード）を考慮して回答する準備をしています。`);
  } else if (commandName === 'instruct') {
    const instruction = interaction.options.getString('instruction', true);
    await interaction.deferReply();
    await ProjectManager.addInstruction(instruction);
    await interaction.editReply(`📥 **指示を受理しました:**\n> ${instruction}\n\nエージェント（Antigravity）がこの指示を読み取り、実行を開始します。完了までお待ちください。`);
  } else if (commandName === 'push') {
    await interaction.deferReply();
    try {
      const result = await ProjectManager.runGitPush();
      let response = `🚀 **GitHubへのプッシュを実行しました**\n\n`;
      response += `🔗 **Repository:** ${result.repoUrl}\n`;
      if (result.pagesUrl) response += `🌐 **Preview:** ${result.pagesUrl}\n`;
      response += `\`\`\`\n${result.output}\n\`\`\``;
      await interaction.editReply(response);
    } catch (error: any) {
      await interaction.editReply(`❌ **プッシュに失敗しました:** ${error.message}`);
    }
  }
});

client.on('messageCreate', async (message: Message) => {
  if (message.author.bot) return;

  const isMentioned = message.mentions.has(client.user!) || message.guild === null;
  const isCommand = message.content.startsWith('/');
  
  if (isMentioned || isCommand) {
    console.log(`Handling message: "${message.content}" (isCommand: ${isCommand})`);
    
    try {
      // スラッシュコマンド同期前のフォールバック処理
      if (message.content.startsWith('/status')) {
        const summary = await ProjectManager.getProgressSummary();
        await message.reply(summary);
        return;
      }
      
      if (message.content.startsWith('/read')) {
        const parts = message.content.split(' ');
        if (parts.length < 2) {
          await message.reply('読み取りたいパスを指定してください（例: `/read src/utils/discord-client.ts`）');
          return;
        }
        const filePath = parts[1];
        const content = await ProjectManager.readFile(filePath);
        await message.reply(`📄 **File: ${filePath}**\n\`\`\`typescript\n${content}\n\`\`\``);
        return;
      }

      if (message.content.startsWith('/ask')) {
        const query = message.content.replace('/ask', '').trim();
        await message.reply(`💡 **相談内容:** ${query}\n\nエージェントとしての回答を生成中です... (現在は準備段階のため、後ほど本格的な統合を行います)`);
        return;
      }

      if (message.content.startsWith('/instruct')) {
        const instruction = message.content.replace('/instruct', '').trim();
        if (!instruction) {
          await message.reply('指示内容を入力してください（例: `/instruct 〇〇を作って`）');
          return;
        }
        await ProjectManager.addInstruction(instruction);
        await message.reply(`📥 **指示を受理しました:**\n> ${instruction}\n\nエージェントが作業を開始します。`);
        return;
      }

      // メンション時のデフォルト挙動 or 自然言語での指示
      if (isMentioned) {
        const text = message.content.replace(`<@${client.user!.id}>`, '').trim();
        
        // 指示文っぽいキーワードが含まれているか判定
        const isInstructionLike = /指示|やって|作って|作成|追加|変更|修正|調べて|削除|プッシュ|push/.test(text);

        if (isInstructionLike) {
          console.log(`Natural language instruction detected: "${text}"`);
          
          if (/プッシュ|push/.test(text)) {
            await message.reply('🚀 **GitHubへのプッシュを開始します...**');
            try {
              const result = await ProjectManager.runGitPush();
              let response = `✅ **プッシュが完了しました！**\n\n`;
              response += `🔗 **Repository:** ${result.repoUrl}\n`;
              if (result.pagesUrl) response += `🌐 **Preview:** ${result.pagesUrl}\n`;
              response += `\`\`\`\n${result.output}\n\`\`\``;
              await message.reply(response);
            } catch (error: any) {
              await message.reply(`❌ **プッシュに失敗しました:** ${error.message}`);
            }
            return;
          }

          await ProjectManager.addInstruction(text);
          await message.reply(`📥 **日本語での指示を受理しました:**\n> ${text}\n\nエージェントが作業を開始します。`);
          return;
        }

        console.log('Sending default reply for mention...');
        const summary = await ProjectManager.getProgressSummary();
        await message.reply(`こんにちは！Antigravityです。現在の進捗はこんな感じです：\n\n${summary}\n\n具体的な指示は「〇〇を作って」のように話しかけてもらうか、\`/instruct\` を使ってくださいね。`);
        console.log('Reply sent successfully.');
      }
    } catch (error) {
      console.error('Error handling message:', error);
    }
  }
});

const token = process.env.DISCORD_TOKEN;
if (token) {
  console.log('Token found, attempting login...');
  client.login(token).catch(err => {
    console.error('Login failed:', err);
  });
} else {
  console.error('DISCORD_TOKEN is not defined in .env');
}

/**
 * 特定のチャンネルに通知を送信する
 */
export const notify = async (message: string) => {
  const channelId = process.env.NOTIFICATION_ID;
  if (!channelId) {
    console.error('NOTIFICATION_ID is not defined in .env');
    return;
  }

  try {
    const channel = await client.channels.fetch(channelId);
    if (channel && 'send' in channel && typeof (channel as any).send === 'function') {
      await (channel as any).send(message);
      console.log('Notification sent successfully.');
    } else {
      console.error('Channel is not a sendable text channel or not found.');
    }
  } catch (error) {
    console.error('Error sending notification:', error);
  }
};

export default client;
