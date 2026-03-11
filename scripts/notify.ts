import client, { notify } from '../src/utils/discord-client.ts';

const message = process.argv[2];

if (!message) {
  console.error('Usage: tsx scripts/notify.ts "Your message"');
  process.exit(1);
}

// clientReadyイベントを待ってから通知を送信する
client.once('clientReady', async () => {
  await notify(message);
  // 送信後、少し待って終了
  setTimeout(() => process.exit(0), 1000);
});
