function getDetailSalesOrders() {
    // API endpoint configuration
    const API_BASE_URL = "http://localhost:8000"; // Update this with your actual API base URL
    const API_ENDPOINT = "/api/detail-sales-orders";

    // Spreadsheet configuration
    const SHEET_NAME = "Detail Sales Orders";
    const HEADERS = [
        "ID",
        "Product",
        "Sales Order ID",
        "Delivery Date",
        "Quantity",
        "Price",
        "Total",
        "For",
        "Payment Status",
        "Delivery Status",
        "Store",
        "Order By",
        "Created At",
        "Updated At",
    ];

    try {
        // Make API request
        const response = UrlFetchApp.fetch(API_BASE_URL + API_ENDPOINT, {
            method: "get",
            headers: {
                Accept: "application/json",
                Authorization:
                    "Bearer " +
                    PropertiesService.getScriptProperties().getProperty(
                        "API_TOKEN"
                    ),
            },
            muteHttpExceptions: true,
        });

        const responseCode = response.getResponseCode();
        if (responseCode !== 200) {
            throw new Error(
                "Failed to fetch data. HTTP Status: " + responseCode
            );
        }

        const jsonResponse = JSON.parse(response.getContentText());
        if (!jsonResponse.success) {
            throw new Error("API returned error status");
        }

        const data = jsonResponse.data;

        // Get or create spreadsheet
        const ss = SpreadsheetApp.getActiveSpreadsheet();
        let sheet = ss.getSheetByName(SHEET_NAME);
        if (!sheet) {
            sheet = ss.insertSheet(SHEET_NAME);
        }

        // Clear existing data
        sheet.clear();

        // Set headers
        sheet.getRange(1, 1, 1, HEADERS.length).setValues([HEADERS]);
        sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");

        if (data && data.length > 0) {
            // Prepare data for spreadsheet
            const spreadsheetData = data.map((item) => [
                item.id,
                item.product,
                item.sales_order_id,
                item.delivery_date,
                item.quantity,
                item.price,
                item.total,
                item.for,
                item.payment_status,
                item.delivery_status,
                item.store,
                item.order_by,
                item.created_at,
                item.updated_at,
            ]);

            // Write data to spreadsheet
            sheet
                .getRange(2, 1, spreadsheetData.length, HEADERS.length)
                .setValues(spreadsheetData);

            // Auto-resize columns
            sheet.autoResizeColumns(1, HEADERS.length);

            // Format number columns
            const priceColumns = [6, 7]; // Price, Total columns
            priceColumns.forEach((col) => {
                sheet
                    .getRange(2, col, spreadsheetData.length, 1)
                    .setNumberFormat("#,##0.00");
            });

            // Format date columns
            const dateColumns = [4, 13, 14]; // Delivery Date, Created At, Updated At columns
            dateColumns.forEach((col) => {
                sheet
                    .getRange(2, col, spreadsheetData.length, 1)
                    .setNumberFormat("yyyy-mm-dd hh:mm:ss");
            });
        }

        Logger.log("Data successfully updated");
    } catch (error) {
        Logger.log("Error: " + error.toString());
        throw error;
    }
}

// Add a custom menu to run the sync
function onOpen() {
    const ui = SpreadsheetApp.getUi();
    ui.createMenu("API Sync")
        .addItem("Sync Detail Sales Orders", "getDetailSalesOrders")
        .addToUi();
}

// Implement this function to return your API token
function getApiToken() {
    // You can store the token in Script Properties
    const scriptProperties = PropertiesService.getScriptProperties();
    return scriptProperties.getProperty("API_TOKEN");
}
