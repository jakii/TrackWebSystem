function formatBytes(bytes) {
  const units = ['B', 'KB', 'MB', 'GB', 'TB'];
  let i = 0;
  while (bytes >= 1024 && i < units.length - 1) {
    bytes /= 1024;
    i++;
  }
  return bytes.toFixed(1) + ' ' + units[i];
}

document.addEventListener("DOMContentLoaded", function () {
  // Count-up animation for numbers
  document.querySelectorAll(".count-up").forEach(counter => {
    const target = +counter.getAttribute("data-target");
    let current = 0;
    const stepTime = Math.max(15, Math.floor(1500 / target));
    const update = () => {
      current++;
      counter.textContent = current;
      if (current < target) {
        setTimeout(update, stepTime);
      } else {
        counter.textContent = target.toLocaleString();
      }
    };
    update();
  });

  // Count-up animation for storage sizes
  document.querySelectorAll(".count-bytes").forEach(counter => {
    const bytes = +counter.getAttribute("data-bytes");
    let current = 0;
    const steps = 100;
    const increment = bytes / steps;
    const stepTime = 15;
    const update = () => {
      current += increment;
      if (current < bytes) {
        counter.textContent = formatBytes(current);
        setTimeout(update, stepTime);
      } else {
        counter.textContent = formatBytes(bytes);
      }
    };
    update();
  });
});
