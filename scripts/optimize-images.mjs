import { readdir, readFile, stat, writeFile } from "node:fs/promises";
import path from "node:path";
import sharp from "sharp";
import { optimize as optimizeSvg } from "svgo";

const imagesRoot = path.resolve("assets/images");

const rasterExtensions = new Set([".jpg", ".jpeg", ".png", ".webp"]);
const svgExtensions = new Set([".svg"]);

const walk = async (directory) => {
  const entries = await readdir(directory, { withFileTypes: true });
  const files = await Promise.all(
    entries.map(async (entry) => {
      const fullPath = path.join(directory, entry.name);

      if (entry.isDirectory()) {
        return walk(fullPath);
      }

      return fullPath;
    })
  );

  return files.flat();
};

const optimizeRaster = async (filePath, extension) => {
  const image = sharp(filePath, { animated: false });
  let optimized;

  if (extension === ".jpg" || extension === ".jpeg") {
    optimized = await image.jpeg({ quality: 82, mozjpeg: true }).toBuffer();
  } else if (extension === ".png") {
    optimized = await image.png({ compressionLevel: 9, progressive: true }).toBuffer();
  } else if (extension === ".webp") {
    optimized = await image.webp({ quality: 82 }).toBuffer();
  } else {
    return;
  }

  await writeFile(filePath, optimized);
};

const optimizeSvgFile = async (filePath) => {
  const source = await readFile(filePath, "utf8");
  const result = optimizeSvg(source, {
    path: filePath,
    multipass: true,
  });

  if ("data" in result) {
    await writeFile(filePath, result.data, "utf8");
  }
};

const run = async () => {
  try {
    const rootStats = await stat(imagesRoot);

    if (!rootStats.isDirectory()) {
      process.exit(0);
    }
  } catch {
    process.exit(0);
  }

  const files = await walk(imagesRoot);

  for (const filePath of files) {
    const extension = path.extname(filePath).toLowerCase();

    if (rasterExtensions.has(extension)) {
      await optimizeRaster(filePath, extension);
      continue;
    }

    if (svgExtensions.has(extension)) {
      await optimizeSvgFile(filePath);
    }
  }
};

run().catch((error) => {
  console.error(error);
  process.exit(1);
});
