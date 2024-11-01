// Save the code to the clipboard by clicking on it
document.addEventListener('DOMContentLoaded', function() {
  const blockCode = document.querySelector('.block-code');
  const savedElement = document.querySelector('.saved');

  // Check if elements with classes .block_code and .saved exist
  if (blockCode && savedElement) {
    blockCode.addEventListener('click', function() {
      // Create a temporary textarea element
      const textarea = document.createElement('textarea');
      textarea.value = blockCode.innerText;
      document.body.appendChild(textarea);

      // Copy text from textarea to clipboard
      textarea.select();
      document.execCommand('copy');

      // Remove the temporary textarea
      document.body.removeChild(textarea);

      // Show the saved element smoothly
      savedElement.style.opacity = '1';

      // Hide the saved element smoothly after 2 seconds
      setTimeout(function() {
        savedElement.style.opacity = '0';
      }, 2000);
    });
  }
});