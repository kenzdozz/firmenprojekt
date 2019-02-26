const fetchCall = async (url, method = 'GET', data = null) => {
    const config = {
        method,
        body: data
    };
    try {
        const resData = await fetch(url, config);
        const response = await resData.json();
        return response;
    } catch (error) {
        console.log(error)
        return {};
    }
}

const deleteRecord = async (e) => {
    e.preventDefault();
    const id = e.target.closest('tr').dataset.id;
    if (!confirm('Delete this record?')) return false;
    const response = await fetchCall(`/delete/${id}`);
    if (response.status >= 400) {
        alert(response.error);
        return false;
    }
    document.querySelector(`#item-${id}`).remove();
    alert(response.message);
    if (!document.querySelectorAll('tbody tr').length) {
        document.querySelector('.empty').innerHTML = 'No records found.';
    }
}

const formInputListener = form => {
    for (let element of form.elements) {
        element.oninput = event => {
            let genErrorSpan = form.querySelector('.form-error.gen-error');
            let errorSpan = element.closest('.input-group').querySelector('.form-error');
            if (genErrorSpan) genErrorSpan.innerHTML = '';
            if (errorSpan) errorSpan.innerHTML = '';
        }
    }
}

const handleFieldErrors = (form, response) => {
    try {
        if (response.fields)
            for (const field in response.fields) {
                const errorSpan = form[field].closest('.input-group').querySelector('.form-error');
                errorSpan.innerHTML = response.fields[field];
            }
        const genError = form.querySelector('.gen-error');
        if (genError) genError.innerHTML = response.error;
    } catch (err) {
        console.log(err)
    }
}

const populateRecord = (item, action = '') => {
    const record = {
        payment_date: item.payment_date,
        company_name: item.company_name,
        bill_amount: item.bill_amount,
        bill_purpose: item.bill_purpose,
    }
    const table = document.querySelector('table tbody');
    let row;
    if (action === 'edit') {
        row = document.querySelector(`#item-${item.id}`);
        row.innerHTML = '';
    } else {
        row = document.createElement('tr');
        row.id = `item-${item.id}`;
    }
    row.dataset.id = item.id;

    let editBtn = document.createElement('a');
    editBtn.classList.add('edit');
    editBtn.innerHTML = '<i class="fa fa-pencil"></i>';
    editBtn.href = 'javascript:;';
    editBtn.onclick = editModal;

    let deleteBtn = document.createElement('a');
    deleteBtn.classList.add('delete')
    deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
    deleteBtn.href = 'javascript:;';
    deleteBtn.onclick = deleteRecord;

    let data = document.createElement('td');
    data.append(editBtn);
    data.append(deleteBtn);
    row.append(data)

    for (let prop in record) {
        const data = document.createElement('td');
        if (prop === 'company_name') {
            data.innerHTML = `${record.company_name}<br>${item.company_id}`;
        } else if (prop === 'bill_amount') {
            data.innerHTML = `&euro; ${record.bill_amount.toLocaleString(undefined, {minimumFractionDigits: 2})}`
        } else {
            data.innerHTML = record[prop];
        }
        row.append(data)
    }
    document.querySelector('.empty').innerHTML = '';

    if (action === 'edit') return false;
    if (action === 'prepend') return table.prepend(row);
    table.append(row);
};

document.querySelector('#manageBills form').onsubmit = async function (event) {
    const isEdit = this.dataset.id ? true : false;
    event.preventDefault();
    const formData = new FormData(this);
    const url = isEdit ? `/update/${this.dataset.id}` : '/create';
    const response = await fetchCall(url, 'POST', formData);
    console.log(response)
    if (!response.status || response.status >= 400) return handleFieldErrors(this, response);
    populateRecord(response.data, isEdit ? 'edit' : 'prepend');
    document.querySelector('#manageBills .modal-close').click()
}

const editModal = (e) => {
    triggerModal('manageBills');
    const mBill = document.querySelector('#manageBills');
    mBill.querySelector('.modal-title').innerHTML = 'Edit a Record';
    const mpForm = mBill.querySelector('form');
    const dataRow = e.target.closest('tr');
    const data = dataRow.querySelectorAll('td');
    const companyInfo = data[2].innerHTML.split('<br>');
    mpForm.dataset.id = dataRow.dataset.id;
    mpForm['company_name'].value = companyInfo[0];
    mpForm['company_id'].value = companyInfo[1];
    mpForm['payment_date'].value = data[1].innerHTML;
    mpForm['bill_amount'].value = data[3].innerHTML.split(' ')[1].replace(',', '');
    mpForm['bill_purpose'].value = data[4].innerHTML;
}

document.querySelectorAll('a.delete').forEach(deleteBtn => {
    deleteBtn.onclick = deleteRecord;
})
document.querySelectorAll('a.edit').forEach(deleteBtn => {
    deleteBtn.onclick = editModal;
})


const emptyForm = form => {
    for (const element of form.elements) {
        element.value = '';
    }
}

document.querySelectorAll('.modal-close').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('show');
            const mForm = modal.querySelector('form');
            if (mForm) {
                emptyForm(mForm);
                mForm.querySelectorAll('.form-error').forEach(elem => elem.innerHTML = '');
                delete mForm.dataset.id;
            }
        });
    })
})
const triggerModal = modalId => {
    const modal = document.querySelector('#' + modalId);
    modal.classList.add('show');
    modal.querySelector('.modal-content').classList.add('slide-in');
    setTimeout(() => {
        modal.querySelector('.modal-content').classList.remove('slide-in');
    }, 100);
}
document.querySelector('#add').onclick = () => {
    triggerModal('manageBills')
    document.querySelector('#manageBills .modal-title').innerHTML = 'Add a Record';
}

document.querySelector('.nav-toggle').onclick = () => {
    const sideBar = document.querySelector('.sidebar-right');
    sideBar.classList.toggle('show');
};