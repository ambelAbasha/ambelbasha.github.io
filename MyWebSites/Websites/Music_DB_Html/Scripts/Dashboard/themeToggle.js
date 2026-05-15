document.addEventListener('DOMContentLoaded', function () {
    const toggleSwitch = document.getElementById('theme-toggle');
    toggleSwitch.addEventListener('change', function () {
        const isChecked = toggleSwitch.checked;
        const theme = isChecked ? 'light' : 'dark';
        document.body.classList.toggle('light-theme', isChecked);
        document.cookie = `theme=${theme}; path=/; samesite=strict`;
    });
});