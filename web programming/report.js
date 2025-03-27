document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
    
    // Sample data for demonstration
    const sampleData = [
      {
        id: 'MNT-2023-001',
        student: 'John Doe',
        date: '2023-06-15',
        type: 'Electrical',
        location: 'Dorm A, Room 205',
        status: 'completed'
      },
      {
        id: 'MNT-2023-002',
        student: 'Jane Smith',
        date: '2023-06-16',
        type: 'Plumbing',
        location: 'Dorm B, Room 312',
        status: 'in-progress'
      },
      {
        id: 'MNT-2023-003',
        student: 'Robert Johnson',
        date: '2023-06-17',
        type: 'Carpentry',
        location: 'Library, 2nd Floor',
        status: 'pending'
      },
      {
        id: 'MNT-2023-004',
        student: 'Emily Davis',
        date: '2023-06-18',
        type: 'HVAC',
        location: 'Science Building, Lab 3',
        status: 'completed'
      },
      {
        id: 'MNT-2023-005',
        student: 'Michael Wilson',
        date: '2023-06-19',
        type: 'Electrical',
        location: 'Dorm C, Room 108',
        status: 'rejected'
      },
      {
        id: 'MNT-2023-006',
        student: 'Sarah Brown',
        date: '2023-06-20',
        type: 'Plumbing',
        location: 'Dorm A, Room 210',
        status: 'completed'
      }
    ];
    
    // DOM elements
    const reportTypeSelect = document.getElementById('report-type');
    const timePeriodSelect = document.getElementById('time-period');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const statusFilterSelect = document.getElementById('filter-status');
    const exportExcelBtn = document.getElementById('export-excel');
    const exportPdfBtn = document.getElementById('export-pdf');
    const reportDataBody = document.getElementById('report-data');
    const reportSummary = document.getElementById('report-summary');
    
    // Initialize date inputs
    const today = new Date();
    const oneWeekAgo = new Date();
    oneWeekAgo.setDate(today.getDate() - 7);
    
    startDateInput.valueAsDate = oneWeekAgo;
    endDateInput.valueAsDate = today;
    
    // Event listeners
    reportTypeSelect.addEventListener('change', generateReport);
    timePeriodSelect.addEventListener('change', updateDateRange);
    startDateInput.addEventListener('change', generateReport);
    endDateInput.addEventListener('change', generateReport);
    statusFilterSelect.addEventListener('change', generateReport);
    exportExcelBtn.addEventListener('click', exportToExcel);
    exportPdfBtn.addEventListener('click', exportToPdf);
    
    // Initial report generation
    generateReport();
    
    // Functions
    function generateReport() {
      const reportType = reportTypeSelect.value;
      const statusFilter = statusFilterSelect.value;
      const startDate = startDateInput.value;
      const endDate = endDateInput.value;
      
      // Filter data based on selections
      let filteredData = sampleData.filter(item => {
        // Filter by date range
        if (item.date < startDate || item.date > endDate) {
          return false;
        }
        
        // Filter by status if selected
        if (statusFilter && item.status !== statusFilter) {
          return false;
        }
        
        return true;
      });
      
      // Sort by date (newest first)
      filteredData.sort((a, b) => new Date(b.date) - new Date(a.date));
      
      // Update report summary
      reportSummary.textContent = `Showing ${filteredData.length} records`;
      
      // Clear existing table data
      reportDataBody.innerHTML = '';
      
      // Populate table with filtered data
      filteredData.forEach(item => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
          <td>${item.id}</td>
          <td>${item.student}</td>
          <td>${formatDate(item.date)}</td>
          <td>${item.type}</td>
          <td>${item.location}</td>
          <td><span class="status ${item.status}">${formatStatus(item.status)}</span></td>
        `;
        
        reportDataBody.appendChild(row);
      });
    }
    
    function updateDateRange() {
      const period = timePeriodSelect.value;
      const today = new Date();
      const startDate = new Date();
      
      switch(period) {
        case 'today':
          startDate.setDate(today.getDate());
          break;
        case 'week':
          startDate.setDate(today.getDate() - 7);
          break;
        case 'month':
          startDate.setMonth(today.getMonth() - 1);
          break;
        case 'quarter':
          startDate.setMonth(today.getMonth() - 3);
          break;
        case 'year':
          startDate.setFullYear(today.getFullYear() - 1);
          break;
        default:
          // For custom, don't change dates
          return;
      }
      
      startDateInput.valueAsDate = startDate;
      endDateInput.valueAsDate = today;
      
      generateReport();
    }
    
    function formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    }
    
    function formatStatus(status) {
      const statusMap = {
        'pending': 'Pending',
        'in-progress': 'In Progress',
        'completed': 'Completed',
        'rejected': 'Rejected'
      };
      return statusMap[status] || status;
    }
    
    function exportToExcel() {
      // In a real implementation, this would generate an Excel file
      alert('Excel export functionality would be implemented here. This would generate a spreadsheet with the current report data.');
    }
    
    function exportToPdf() {
      // In a real implementation, this would generate a PDF file
      alert('PDF export functionality would be implemented here. This would generate a PDF document with the current report data.');
    }
  });