// Ensure jsPDF and autoTable are available globally for UMD build
if (window.jspdf && window.jspdf.jsPDF) {
    window.jsPDF = window.jspdf.jsPDF;
}
if (window.jspdf && window.jspdf.autoTable) {
    window.autoTable = window.jspdf.autoTable;
}
