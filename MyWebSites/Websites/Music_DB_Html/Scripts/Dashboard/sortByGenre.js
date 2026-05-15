 function sortByGenre() {
                const genreSelect = document.getElementById("genre-select");
                const selectedGenre = genreSelect.value;
                let url = "?lastId=0";

                if (selectedGenre) {
                    url += `&genre=${encodeURIComponent(selectedGenre)}`;
                }

                window.location.href = url;
            }