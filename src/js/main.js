document.addEventListener('DOMContentLoaded', () => {
    
    // Password visibility toggle
   const passwordInputs = document.querySelectorAll('input[type="password"]');

    passwordInputs.forEach(input => {
        const wrapper = document.createElement('div');
        wrapper.className = 'password-wrapper';

        const eyeIconSrc = '/src/icons/visibility.svg';
        const eyeOffIconSrc = '/src/icons/visibility_off.svg';
        const toggleIcon = document.createElement('img');
        toggleIcon.src = eyeIconSrc;
        toggleIcon.alt = 'Afficher le mot de passe';
        toggleIcon.width = 18;
        toggleIcon.height = 18;
        toggleIcon.className = 'toggle-password';
        toggleIcon.setAttribute('role', 'button');
        toggleIcon.setAttribute('tabindex', '0');;

        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(toggleIcon);
        
        toggleIcon.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const icon = toggleIcon.querySelector('img');
            if (icon) {
                icon.src = isPassword ? eyeOffIconSrc : eyeIconSrc;
                icon.alt = isPassword ? 'Off' : 'On';
            }
        });
    });

    
    // Password Requirements
    const passwordInput = document.getElementById('new_password');
    const passwordInputConfirm = document.getElementById('new_password_confirm');
    const submitBtn = document.querySelector('form>button[type=submit]');
    const reqLength = document.getElementById('req-length');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    function updateRequirement(element, isValid) {
        if (isValid) {
            element.className = 'valid';
        } else {
            element.className = 'invalid';
        }
    }

    // Fonction globale de validation
    function checkFormValidity() {
        const val = passwordInput.value;
        const valConfirm = passwordInputConfirm.value;

        const isLengthValid = val.length >= 8;
        const isNumberValid = /\d/.test(val);
        const isSpecialValid = /[^A-Za-z0-9]/.test(val);

        const isMatchValid = (val === valConfirm) && (val !== '');

        updateRequirement(reqLength, isLengthValid);
        updateRequirement(reqNumber, isNumberValid);
        updateRequirement(reqSpecial, isSpecialValid);

        if (isLengthValid && isNumberValid && isSpecialValid && isMatchValid) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    if (passwordInput && passwordInputConfirm) {
        passwordInput.addEventListener('input', checkFormValidity);
        passwordInputConfirm.addEventListener('input', checkFormValidity);
    }
    
    
    //Table sort
    const sortableHeaders = document.querySelectorAll('th:not([no-filter])');

    sortableHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const table = header.closest('table');
            const tbody = table.tBodies[0]; 
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const columnIndex = Array.from(header.parentElement.children).indexOf(header);
            const isAsc = header.classList.contains('sort-asc');
            const direction = isAsc ? -1 : 1; // 1 pour Asc, -1 pour Desc
            
            table.querySelectorAll('th').forEach(th => th.classList.remove('sort-asc', 'sort-desc'));
            header.classList.add(isAsc ? 'sort-desc' : 'sort-asc');

            rows.sort((a, b) => {
                const aContent = a.children[columnIndex]?.textContent.trim() || '';
                const bContent = b.children[columnIndex]?.textContent.trim() || '';
                const aNum = parseFloat(aContent.replace(',', '.'));
                const bNum = parseFloat(bContent.replace(',', '.'));
                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return (aNum - bNum) * direction;
                }
                return aContent.localeCompare(bContent, 'fr', { numeric: true }) * direction;
            });
            rows.forEach(row => tbody.appendChild(row));
        });
    });


    //Table filter
    if(document.querySelector(".table-filters")){
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const tableRows = document.querySelectorAll('tbody tr');
        let currentSearchTerm = '';
        let currentFilter = 'all';

        function filterTable() {
            tableRows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                if (cells.length > 0) {
                    cells.pop(); 
                }
                const rowText = cells.map(cell => cell.textContent).join(' ').toLowerCase();
                const matchesSearch = rowText.includes(currentSearchTerm);
                let matchesFilter = false;

                if (currentFilter === 'all') {
                    matchesFilter = true;
                } else if (currentFilter === 'new') {
                    const tag = row.querySelector('.tag');
                    if (tag && !tag.classList.contains('in-progress') && !tag.classList.contains('closed')) {
                        matchesFilter = true;
                    }
                } else if (currentFilter === 'in-progress') {
                    if (row.querySelector('.tag.in-progress')) matchesFilter = true;
                } else if (currentFilter === 'closed') {
                    if (row.querySelector('.tag.closed')) matchesFilter = true;
                } else if (currentFilter === 'me') {
                    if (row.querySelector('.profile_picture.me')) matchesFilter = true;
                }
                if (matchesSearch && matchesFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search bar input listener
        searchInput.addEventListener('input', (e) => {
            currentSearchTerm = e.target.value.toLowerCase();
            filterTable();
        });

        // Buttons filters event listener
        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.getAttribute('data-filter');
                filterTable();
            });
        });
    }


    //Tag manager
    const wrapper = document.getElementById('tags-wrapper');
    const tagInput = document.getElementById('tag-input');
    const tagsContainer = document.getElementById('tags-container');
    const hiddenInput = document.getElementById('hidden-groups-input');
    const autocompleteList = document.getElementById('autocomplete-list');

    if (wrapper && tagInput && tagsContainer && hiddenInput && autocompleteList) {
        const whitelistData = wrapper.getAttribute('data-whitelist');
        const whitelist = whitelistData ? JSON.parse(whitelistData) : [];

        // Init existing tags table
        let tags = hiddenInput.value ? hiddenInput.value.split(',').map(t => t.trim()).filter(t => t !== '') : [];

        // Render tags
        function renderTags() {
            tagsContainer.innerHTML = '';
            tags.forEach((tag, index) => {
                const tagEl = document.createElement('span');
                tagEl.className = 'tag';
                tagEl.textContent = tag;

                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-btn';
                removeBtn.textContent = '×';
                removeBtn.onclick = () => removeTag(index);

                tagEl.appendChild(removeBtn);
                tagsContainer.appendChild(tagEl);
            });
            hiddenInput.value = tags.join(',');
        }
        // Add a tag
        function addTag(tag) {
            tag = tag.trim();
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                renderTags();
            }
            tagInput.value = '';
            autocompleteList.style.display = 'none';
        }
        // Delete a tag
        function removeTag(index) {
            tags.splice(index, 1);
            renderTags();
        }
        // Global wrapper focus
        wrapper.addEventListener('click', () => {
            tagInput.focus();
        });
        // Keyboard input
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addTag(this.value);
            } else if (e.key === 'Backspace' && this.value === '' && tags.length > 0) {
                removeTag(tags.length - 1);
            }
        });
        // Auto-complete
        tagInput.addEventListener('input', function() {
            const val = this.value.toLowerCase();
            autocompleteList.innerHTML = '';
            if (!val) {
                autocompleteList.style.display = 'none';
                return;
            }

            // Filtrer la liste (contient le texte et n'est pas déjà ajouté)
            const matched = whitelist.filter(w => w.toLowerCase().includes(val) && !tags.includes(w));
            if (matched.length > 0) {
                autocompleteList.style.display = 'block';
                matched.forEach(match => {
                    const li = document.createElement('li');
                    li.className = 'autocomplete-item';
                    li.textContent = match;
                    li.onmousedown = function(e) {
                        e.preventDefault();
                        addTag(match);
                    };
                    autocompleteList.appendChild(li);
                });
            } else {
                autocompleteList.style.display = 'none';
            }
        });
        // Hide tag list
        tagInput.addEventListener('blur', function() {
            autocompleteList.style.display = 'none';
            if (this.value.trim() !== '') {
                addTag(this.value);
            }
        });
        renderTags();
    }
});