// genericEntityManager.js

// Obtener la configuración desde el HTML usando data-attributes
const entityManagerContainer = document.getElementById('entityManagerComponent');

const config = {
    apiUrl: entityManagerContainer.getAttribute('data-api-url') || 'index.php',
    defaultEntity: entityManagerContainer.getAttribute('data-default-entity') || 'usuarios',
    displayColumns: (entityManagerContainer.getAttribute('data-display-columns') || 'id,nombre,email').split(','),
    foreignKey: entityManagerContainer.getAttribute('data-foreign-key') || null // Clave externa en formato "entidad:campo"
};

let entity = config.defaultEntity;
let selectedId = null;
let foreignEntity = null;
let foreignField = null;

// Si existe una clave externa, dividirla en entidad y campo
if (config.foreignKey) {
    [foreignEntity, foreignField] = config.foreignKey.split(':');
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('entity').value = config.defaultEntity;
    loadData();
});

async function loadData() {
  //  alert(1);
    entity = document.getElementById('entity').value || config.defaultEntity;
    const response = await fetch(`${config.apiUrl}?action=${entity}`);
    const data = await response.json();
    renderTable(data);
    generateFormFields(data[0] || {});
}

// Función para generar campos de formulario, incluyendo el selector de la clave externa
function generateFormFields(data) {
    const formFields = document.getElementById('formFields');
    formFields.innerHTML = '';

    // Si existe una clave externa, generar un campo de selección
    if (foreignEntity && foreignField) {
        const foreignFieldContainer = document.createElement('div');
        foreignFieldContainer.classList.add('mb-3');
        
        const label = document.createElement('label');
        label.classList.add('form-label');
        label.textContent = `Seleccionar ${foreignEntity}`;
        foreignFieldContainer.appendChild(label);
        
        const select = document.createElement('select');
        select.id = foreignField;
        select.name = foreignField;
        select.classList.add('form-control');
        foreignFieldContainer.appendChild(select);
        
        formFields.appendChild(foreignFieldContainer);
        
        // Llenar el campo de selección con datos de la entidad relacionada
        loadForeignOptions();
    }

    // Generar campos para las columnas configuradas
    config.displayColumns.forEach(column => {
        if (column === foreignField) return; // Omitir la clave externa, ya que ya tiene su campo

        const formGroup = document.createElement('div');
        formGroup.classList.add('mb-3');

        const label = document.createElement('label');
        label.classList.add('form-label');
        label.textContent = column;
        label.setAttribute('for', column);

        const input = document.createElement('input');
        input.type = 'text';
        input.name = column;
        input.id = column;
        input.classList.add('form-control');

        formGroup.appendChild(label);
        formGroup.appendChild(input);
        formFields.appendChild(formGroup);
    });
}

// Función para cargar las opciones de la entidad relacionada en el selector de búsqueda
async function loadForeignOptions() {
    const response = await fetch(`${config.apiUrl}?action=${foreignEntity}`);
    const data = await response.json();

    const select = document.getElementById(foreignField);
    select.innerHTML = '';

    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id; // Asume que la entidad relacionada tiene un campo 'id' como identificador
        option.textContent = item.nombre || item.name || item.id; // Muestra 'nombre', 'name', o 'id' según esté disponible
        select.appendChild(option);
    });
}

function prepareCreate() {
    selectedId = null;
    document.getElementById('dataForm').reset();
}

function renderTable(data) {
    const table = document.getElementById('dataTable');
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');

    thead.innerHTML = '';
    tbody.innerHTML = '';

    if (data.length === 0) return;

    const headerRow = document.createElement('tr');
    config.displayColumns.forEach(column => {
        const th = document.createElement('th');
        th.textContent = column;
        headerRow.appendChild(th);
    });
    const actionsTh = document.createElement('th');
    actionsTh.textContent = 'Acciones';
    headerRow.appendChild(actionsTh);
    thead.appendChild(headerRow);

    data.forEach(record => {
        const row = document.createElement('tr');
        config.displayColumns.forEach(column => {
            const td = document.createElement('td');
            td.textContent = record[column] || '';
            row.appendChild(td);
        });
        const actionsTd = document.createElement('td');
        actionsTd.innerHTML = `
            <button class="btn btn-warning btn-sm" onclick="editRecord(${record.id})" data-bs-toggle="modal" data-bs-target="#dataModal">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="deleteRecord(${record.id})">Eliminar</button>
        `;
        row.appendChild(actionsTd);
        tbody.appendChild(row);
    });
}

function editRecord(id) {
    selectedId = id;
    fetch(`${config.apiUrl}?action=${entity}&id=${id}`)
        .then(response => response.json())
        .then(data => {
            Object.keys(data).forEach(key => {
                if (document.getElementById(key)) {
                    document.getElementById(key).value = data[key];
                }
            });
        });
}

function deleteRecord(id) {
    fetch(`${config.apiUrl}?action=${entity}&id=${id}`, {
        method: 'DELETE'
    }).then(() => loadData());
}

function handleSubmit(event) {
    event.preventDefault();

    const formData = {};
    Array.from(document.getElementById('dataForm').elements).forEach(input => {
        if (input.name) formData[input.name] = input.value;
    });

    const method = selectedId ? 'PUT' : 'POST';
    const url = selectedId ? `${config.apiUrl}?action=${entity}&id=${selectedId}` : `${config.apiUrl}?action=${entity}`;

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    }).then(() => {
        loadData();
        document.getElementById('dataForm').reset();
        selectedId = null;
        const dataModal = bootstrap.Modal.getInstance(document.getElementById('dataModal'));
        dataModal.hide();
    });
}