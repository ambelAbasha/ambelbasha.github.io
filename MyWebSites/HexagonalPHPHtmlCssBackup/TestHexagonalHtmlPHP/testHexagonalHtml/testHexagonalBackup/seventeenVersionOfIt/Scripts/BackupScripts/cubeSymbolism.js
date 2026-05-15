document.addEventListener('DOMContentLoaded', function () {
    const frontFace = document.querySelector('.face.front');
    const cubeSymbolism = document.querySelector('#cube-symbolism');

    // Event listener for clicking on the cube's front face
    frontFace.addEventListener('click', function () {
        // Toggle the background color and visibility of the text
        if (cubeSymbolism.style.opacity === '1') {
            cubeSymbolism.style.opacity = '0'; // Hide the text
            cubeSymbolism.style.backgroundColor = '#f4f4f400'; // Amber color
        } else {
            cubeSymbolism.style.opacity = '1'; // Show the text
            cubeSymbolism.style.backgroundColor = '#ffffff'; // White color
        }
    });
});
