function filterItems(category) {
    const items = document.querySelectorAll('.p-items');
    items.forEach(item => {
        item.style.display = 'none';
        // item.removeAttribute('data-aos');
    });

    // Reset the text color of all <a> elements to the default
    document.querySelectorAll('.filter-colour').forEach(link => {
        link.style.color = ''; // Reset to default color
    });

    // Show items that match the selected category
    if (category === 'All') {
        items.forEach(item => item.style.display = 'grid');
        // Highlight the 'All projects' link
        document.querySelector('.filter-colour.active-filter').style.color = '#b51761ff'; 
    } else {
        const filteredItems = document.querySelectorAll(`.p-items[data-category~="${category}"]`);
        filteredItems.forEach(item => {
            // - or change to grid
            item.style.display = 'grid';
            // // item.style.display = 'flex';
            // item.style.gridColumn = 'auto';
            // item.style.gridRow = 'auto';
        });

        // Highlight the selected category link
        document.querySelector(`.filter-colour[onclick="filterItems('${category}')"]`).style.color = '#b1368dff'; // Change color as needed

    }
    
}
