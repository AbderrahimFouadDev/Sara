class HomeEditor {
    constructor() {
        this.currentSection = null;
        this.changes = {};
        this.initialize();
    }

    initialize() {
        this.bindEvents();
        this.loadSections();
    }

    bindEvents() {
        // Section selection
        const sections = document.querySelectorAll('.editable-sections li');
        sections.forEach(section => {
            section.addEventListener('click', () => this.selectSection(section));
        });

        // Save changes
        const saveBtn = document.querySelector('.save-changes-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.saveChanges());
        }
    }

    selectSection(sectionElement) {
        // Remove active class from all sections
        document.querySelectorAll('.editable-sections li').forEach(s => {
            s.classList.remove('active');
        });

        // Add active class to selected section
        sectionElement.classList.add('active');

        // Load section content
        this.currentSection = sectionElement.textContent.toLowerCase();
        this.loadSectionContent(this.currentSection);
    }

    loadSections() {
        // Load initial content for the first section
        const firstSection = document.querySelector('.editable-sections li');
        if (firstSection) {
            this.selectSection(firstSection);
        }
    }

    async loadSectionContent(section) {
        try {
            // Here you would typically make an API call to get the section content
            // For now, we'll use placeholder content
            const content = await this.fetchSectionContent(section);
            this.displaySectionContent(content);
        } catch (error) {
            console.error('Error loading section content:', error);
        }
    }

    async fetchSectionContent(section) {
        // This would be replaced with actual API call
        const placeholderContent = {
            'en-tête': {
                title: 'Bienvenue sur FacturePro',
                subtitle: 'La solution de facturation simple et efficace'
            },
            'à propos': {
                content: 'FacturePro est votre partenaire de confiance pour la gestion de factures...'
            },
            'services': {
                items: [
                    'Facturation automatique',
                    'Suivi des paiements',
                    'Rapports détaillés'
                ]
            },
            'contact': {
                email: 'contact@facturepro.com',
                phone: '+33 1 23 45 67 89'
            }
        };

        return placeholderContent[section] || {};
    }

    displaySectionContent(content) {
        const editArea = document.querySelector('.edit-area');
        if (!editArea) return;

        let html = '<div class="editor-fields">';
        
        // Create form fields based on content structure
        Object.entries(content).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                html += `
                    <div class="editor-field">
                        <label>${key}</label>
                        <div class="list-editor">
                            ${value.map((item, index) => `
                                <div class="list-item">
                                    <input type="text" value="${item}" data-index="${index}">
                                    <button class="remove-item">×</button>
                                </div>
                            `).join('')}
                            <button class="add-item">+ Ajouter</button>
                        </div>
                    </div>
                `;
            } else {
                html += `
                    <div class="editor-field">
                        <label>${key}</label>
                        ${key === 'content' 
                            ? `<textarea rows="5">${value}</textarea>`
                            : `<input type="text" value="${value}">`
                        }
                    </div>
                `;
            }
        });

        html += '</div>';
        editArea.innerHTML = html;

        // Bind events for list editors
        this.bindListEditorEvents();
    }

    bindListEditorEvents() {
        // Add item buttons
        document.querySelectorAll('.add-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const listEditor = e.target.closest('.list-editor');
                const newItem = document.createElement('div');
                newItem.className = 'list-item';
                newItem.innerHTML = `
                    <input type="text" value="" data-index="${listEditor.querySelectorAll('.list-item').length}">
                    <button class="remove-item">×</button>
                `;
                listEditor.insertBefore(newItem, e.target);
            });
        });

        // Remove item buttons
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.target.closest('.list-item').remove();
            });
        });
    }

    async saveChanges() {
        try {
            const editArea = document.querySelector('.edit-area');
            const fields = editArea.querySelectorAll('.editor-field');
            const data = {};

            fields.forEach(field => {
                const label = field.querySelector('label').textContent;
                const listEditor = field.querySelector('.list-editor');

                if (listEditor) {
                    // Handle list items
                    data[label] = Array.from(listEditor.querySelectorAll('input'))
                        .map(input => input.value)
                        .filter(value => value.trim() !== '');
                } else {
                    // Handle single fields
                    const input = field.querySelector('input, textarea');
                    data[label] = input.value;
                }
            });

            // Here you would typically make an API call to save the changes
            console.log('Saving changes for section:', this.currentSection, data);
            
            // Show success message
            this.showNotification('Modifications enregistrées avec succès');
        } catch (error) {
            console.error('Error saving changes:', error);
            this.showNotification('Erreur lors de l\'enregistrement', 'error');
        }
    }

    showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        // Add to document
        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.home-editor-section')) {
        new HomeEditor();
    }
}); 