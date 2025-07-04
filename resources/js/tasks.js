document.addEventListener('DOMContentLoaded', function () {
    const filterTasksBtn = document.getElementById('filterTasks');
    const collectorFilter = document.getElementById('collectorFilter');
    const statusFilter = document.getElementById('statusFilter');
    const tasksBody = document.getElementById('tasksBody');

    filterTasksBtn.addEventListener('click', function () {
        const collectorId = collectorFilter.value;
        const status = statusFilter.value;

        fetch('{{ route("admin.collector-tasks.data") }}' + `?collector_id=${collectorId}&status=${status}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tasksBody.innerHTML = '';
                data.data.forEach(task => {
                    const statusBadgeClass = {
                        'completed': 'bg-green-100 text-green-800',
                        'active': 'bg-blue-100 text-blue-800',
                        'pending': 'bg-gray-100 text-gray-800',
                        'cancelled': 'bg-red-100 text-red-800'
                    }[task.status] || 'bg-gray-100 text-gray-800';

                    const row = `
                        <tr>
                            <td class="px-6 py-4">${task.nasabah.name}</td>
                            <td class="px-6 py-4">${task.loan_id || '-'}</td>
                            <td class="px-6 py-4">${new Date(task.assigned_date).toLocaleDateString('id-ID')}</td>
                            <td class="px-6 py-4">${new Date(task.due_date).toLocaleDateString('id-ID')}</td>
                            <td class="px-6 py-4">
                                <form action="/collector-tasks/${task.id}/status" method="POST">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                        <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="active" ${task.status === 'active' ? 'selected' : ''}>Active</option>
                                        <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                                        <option value="cancelled" ${task.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <a href="/collector-tasks/${task.id}/edit" class="text-blue-500 hover:text-blue-700 mr-2">Edit</a>
                                <form action="/collector-tasks/${task.id}" method="POST" class="inline">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    tasksBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                alert(data.message || 'Failed to load tasks.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching tasks.');
        });
    });
});