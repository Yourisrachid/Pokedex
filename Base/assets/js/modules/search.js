export function search() {
    
    document.getElementById('toggle-advanced-search').addEventListener('click', function() {
        var advancedSearch = document.getElementById('advanced-search');
        if (advancedSearch.classList.contains('hidden')) {
            advancedSearch.classList.remove('hidden');
            advancedSearch.classList.add('visible');
        } else {
            advancedSearch.classList.remove('visible');
            advancedSearch.classList.add('hidden');
        }
    });

}