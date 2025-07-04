import { promises as fs } from 'fs';
import path from 'path';

export default async function handler(req, res) {
  if (req.method !== 'GET') return res.status(405).json({ error: 'فقط GET مجاز است' });

  const filePath = path.join(process.cwd(), 'messages.json');
  try {
    const fileData = await fs.readFile(filePath, 'utf8');
    const msgs = JSON.parse(fileData);
    res.status(200).json(msgs);
  } catch (err) {
    res.status(200).json([]); // اگر فایل وجود ندارد آرایه خالی بده
  }
}
