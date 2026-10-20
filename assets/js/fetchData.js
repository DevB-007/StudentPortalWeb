document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('eventsContainer');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const paginationControls = document.getElementById('paginationControls');
    const loadingState = document.getElementById('loadingState');
    const errorState = document.getElementById('errorState');

    let allEvents = [];
    let currentPage = 1;
    const itemsPerPage = 6;

    async function fetchEvents() {
        try {
            const cachedData = localStorage.getItem('eventsCache');
            if (cachedData) {
                allEvents = JSON.parse(cachedData);
                loadingState.style.display = 'none';
                renderEvents();
            }

            const response = await fetch('../assets/data/events.json');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            allEvents = data;
            localStorage.setItem('eventsCache', JSON.stringify(data));
            
            loadingState.style.display = 'none';
            errorState.style.display = 'none';
            renderEvents();
        } catch (error) {
            loadingState.style.display = 'none';
            if (allEvents.length === 0) {
                errorState.textContent = 'Failed to load events. Please try again later.';
                errorState.style.display = 'block';
            }
        }
    }

    function renderEvents() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;

        const filteredEvents = allEvents.filter(event => {
            const matchesSearch = event.title.toLowerCase().includes(searchTerm);
            const matchesCategory = category === 'All' || event.category === category;
            return matchesSearch && matchesCategory;
        });

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedEvents = filteredEvents.slice(startIndex, endIndex);

        container.innerHTML = '';

        if (paginatedEvents.length === 0) {
            container.innerHTML = '<p>No events found matching your criteria.</p>';
            paginationControls.innerHTML = '';
            return;
        }

        paginatedEvents.forEach(event => {
            const card = document.createElement('div');
            card.style.border = '1px solid var(--border)';
            card.style.padding = '1rem';
            card.style.borderRadius = '4px';
            card.style.backgroundColor = 'var(--bg-light)';
            
            card.innerHTML = `
                <h3 style="margin-bottom: 0.5rem; color: var(--primary);">${event.title}</h3>
                <p style="font-size: 0.9rem; margin-bottom: 0.25rem;"><strong>Category:</strong> ${event.category}</p>
                <p style="font-size: 0.9rem; margin-bottom: 1rem;"><strong>Date:</strong> ${event.date}</p>
                <a href="event-register.html" class="btn" style="width: 100%; padding: 0.5rem;">Register</a>
            `;
            container.appendChild(card);
        });

        renderPagination(filteredEvents.length);
    }

    function renderPagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        paginationControls.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = 'btn';
            btn.style.padding = '0.5rem 1rem';
            if (i === currentPage) {
                btn.style.opacity = '0.6';
                btn.style.cursor = 'default';
            } else {
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderEvents();
                });
            }
            paginationControls.appendChild(btn);
        }
    }

    searchInput.addEventListener('input', () => {
        currentPage = 1;
        renderEvents();
    });

    categoryFilter.addEventListener('change', () => {
        currentPage = 1;
        renderEvents();
    });

    if (container) {
        fetchEvents();
    }
});