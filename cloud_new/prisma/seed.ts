import { PrismaClient } from '@prisma/client';
import bcrypt from 'bcryptjs';

const prisma = new PrismaClient();

async function main() {
  console.log('Cleaning up database...');
  await prisma.backedProject.deleteMany();
  await prisma.backingLevel.deleteMany();
  await prisma.comment.deleteMany();
  await prisma.report.deleteMany();
  await prisma.message.deleteMany();
  await prisma.project.deleteMany();
  await prisma.category.deleteMany();
  await prisma.area.deleteMany();

  console.log('Seeding database...');
  
  // 1. Create Categories and Areas
  const techCategory = await prisma.category.create({ data: { name: 'テクノロジー' } });
  const foodCategory = await prisma.category.create({ data: { name: 'フード' } });
  const artCategory = await prisma.category.create({ data: { name: 'アート' } });

  const tokyoArea = await prisma.area.create({ data: { name: '東京' } });
  const osakaArea = await prisma.area.create({ data: { name: '大阪' } });
  const globalArea = await prisma.area.create({ data: { name: 'グローバル' } });

  // 2. Create a dummy user
  const hashedPassword = await bcrypt.hash('password123', 10);
  const user = await prisma.user.upsert({
    where: { email: 'demo@example.com' },
    update: {},
    create: {
      email: 'demo@example.com',
      nickname: 'Demo User',
      password: hashedPassword,
      active: true,
    },
  });

  console.log('User created:', user.email);

  // 3. Create another user (Project Owner)
  const owner = await prisma.user.upsert({
    where: { email: 'owner@example.com' },
    update: {},
    create: {
      email: 'owner@example.com',
      nickname: 'Creative Studio',
      password: hashedPassword,
      active: true,
    },
  });

  // 4. Create some projects
  const projectsData = [
    {
      projectName: '革新的なスマート・クリスタルデバイスの開発',
      description: '人々の生活をより豊かに、よりスマートにする次世代のデバイスを目指しています。深海からのインスピレーションを得た、全く新しいインターフェース。',
      goalAmount: 2000000,
      collectedAmount: 1500000,
      backers: 234,
      collectionEndDate: new Date(Date.now() + 12 * 24 * 60 * 60 * 1000), // 12 days later
      opened: 'yes',
      active: 'yes',
      ownerId: owner.id,
      categoryId: techCategory.id,
      areaId: globalArea.id,
      imageUrl: 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?q=80&w=800&auto=format&fit=crop',
    },
    {
      projectName: '極上の睡眠を届ける AI 搭載スマートピロー',
      description: 'あなたの睡眠サイクルをAIが分析し、最適な高度と温度をリアルタイムで微調整。朝のスッキリ感が劇的に変わります。',
      goalAmount: 1000000,
      collectedAmount: 850000,
      backers: 156,
      collectionEndDate: new Date(Date.now() + 25 * 24 * 60 * 60 * 1000), // 25 days later
      opened: 'yes',
      active: 'yes',
      ownerId: owner.id,
      categoryId: techCategory.id,
      areaId: tokyoArea.id,
      imageUrl: 'https://images.unsplash.com/photo-1520206151081-79c099c279e8?q=80&w=800&auto=format&fit=crop',
    },
    {
      projectName: '都市型マイクロ菜園：自動給水プランター',
      description: '忙しくても植物を枯らさない。部屋の光量に合わせて自動でLEDと水を調整する、都市生活者のためのミニマルな菜園キット。',
      goalAmount: 500000,
      collectedAmount: 120000,
      backers: 45,
      collectionEndDate: new Date(Date.now() + 5 * 24 * 60 * 60 * 1000), // 5 days later
      opened: 'yes',
      active: 'yes',
      ownerId: user.id, // Demo user is the owner of this one
      categoryId: foodCategory.id,
      areaId: osakaArea.id,
      imageUrl: 'https://images.unsplash.com/photo-1585336139118-1216694e9766?q=80&w=800&auto=format&fit=crop',
    },
  ];

  const projects = [];
  for (const p of projectsData) {
    const project = await prisma.project.create({
      data: {
        ...p,
        backingLevels: {
          create: [
            {
              name: 'ベーシック支援',
              investAmount: 3000,
              returnAmount: 'お礼のメール + 感謝のビデオ',
            },
            {
              name: 'スタンダード支援',
              investAmount: 10000,
              returnAmount: '製品の先行予約権 + オリジナルステッカー',
            },
            {
              name: 'プレミアム支援',
              investAmount: 50000,
              returnAmount: '製品本体 + 限定カラー + 開発記ブックレット',
            },
          ],
        },
      },
      include: {
        backingLevels: true
      }
    });
    projects.push(project);
    console.log('Project created:', project.projectName);
  }

  // 5. Have demo user back some projects
  await prisma.backedProject.create({
    data: {
      projectId: projects[0].id,
      userId: user.id,
      backingLevelId: projects[0].backingLevels[1].id, // Standard
      investAmount: 10000,
      status: 'authorized',
    }
  });

  await prisma.backedProject.create({
    data: {
      projectId: projects[1].id,
      userId: user.id,
      backingLevelId: projects[1].backingLevels[0].id, // Basic
      investAmount: 3000,
      status: 'authorized',
    }
  });

  console.log('Seeding completed.');
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
