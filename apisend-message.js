import { promises as fs } from 'fs';
import path from 'path';

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).json({ error: 'فقط POST مجاز است' });

  const { name = 'کاربر', message } = req.body;
  if (!message || !message.trim()) return res.status(400).json({ error: 'پیام خالی است' });

  const filePath = path.join(process.cwd(), 'messages.json');
  let msgs = [];
  try {
    const fileData = await fs.readFile(filePath, 'utf8');
    msgs = JSON.parse(fileData);
  } catch (err) {
    // فایل وجود ندارد
  }

  msgs.push({ name, message, date: new Date().toISOString() });
  await fs.writeFile(filePath, JSON.stringify(msgs, null, 2));

  res.status(200).json({ ok: true });
}
