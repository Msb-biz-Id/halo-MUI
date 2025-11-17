import { PurgeCSS } from 'purgecss'
import fs from 'fs'
import path from 'path'

async function runPurge() {
  const cssFolder = 'assets/css/theme'
  const outputFolder = 'assets/css/clean'

  // ✅ সব CSS ফাইল খুঁজে বের করা
  const cssFiles = fs.readdirSync(cssFolder)
    .filter(file => file.endsWith('.css'))
    .map(file => `${cssFolder}/${file}`)

  // ✅ যেসব HTML ও JS ফাইল স্ক্যান করবে
  const contentPaths = [
    'main/**/*.html',
    'demo-two/**/*.html',
    'demo-three/**/*.html',
    'demo-four/**/*.html',
    'demo-five/**/*.html',
    'demo-six/**/*.html',
    'demo-seven/**/*.html',
    'demo-eight/**/*.html',
    'assets/js/**/*.js'
  ]

  // ✅ clean ফোল্ডার না থাকলে তৈরি করা
  fs.mkdirSync(outputFolder, { recursive: true })

  for (const cssFile of cssFiles) {
    const result = await new PurgeCSS().purge({
      content: contentPaths,
      css: [cssFile],
    })

    const outputFile = path.join(outputFolder, path.basename(cssFile))
    fs.writeFileSync(outputFile, result[0].css, 'utf-8')
    console.log(`✅ Cleaned: ${outputFile}`)
  }
}

runPurge()
