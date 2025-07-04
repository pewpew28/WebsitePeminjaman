<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Edit Collector Task</h1>

        <form action="{{ route('admin.collector-tasks.update', $collectorTask) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="collector_id" class="block text-sm font-medium text-gray-700">Collector</label>
                    <select name="collector_id" id="collector_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        @foreach($collectors as $collector)
                            <option value="{{ $collector->id }}" {{ $collector->id == $collectorTask->collector_id ? 'selected' : '' }}>{{ $collector->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nasabah_id" class="block text-sm font-medium text-gray-700">Nasabah</label>
                    <select name="nasabah_id" id="nasabah_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        @foreach($nasabahs as $nasabah)
                            <option value="{{ $nasabah->id }}" {{ $nasabah->id == $collectorTask->nasabah_id ? 'selected' : '' }}>{{ $nasabah->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="loan_id" class="block text-sm font-medium text-gray-700">Loan (Optional)</label>
                    <select name="loan_id" id="loan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">Select Loan</option>
                        @foreach($loans as $loan)
                            <option value="{{ $loan->id }}" {{ $loan->id == $collectorTask->loan_id ? 'selected' : '' }}>Loan #{{ $loan->id }} - {{ $loan->nasabah->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="assigned_date" class="block text-sm font-medium text-gray-700">Assigned Date</label>
                    <input type="date" name="assigned_date" id="assigned_date" value="{{ $collectorTask->assigned_date->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" id="due_date" value="{{ $collectorTask->due_date->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="pending" {{ $collectorTask->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ $collectorTask->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ $collectorTask->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $collectorTask->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="amount_collected_during_task" class="block text-sm font-medium text-gray-700">Amount Collected</label>
                    <input type="number" name="amount_collected_during_task" id="amount_collected_during_task" value="{{ $collectorTask->amount_collected_during_task }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" min="0" step="0.01">
                </div>

                <div class="col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">{{ $collectorTask->notes }}</textarea>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Task</button>
                <a href="{{ route('admin.collector-tasks.show', $collectorTask->collector) }}" class="ml-4 text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>