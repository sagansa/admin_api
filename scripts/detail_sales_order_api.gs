function getDetailSalesOrders() {
    // API endpoint configuration
    const API_BASE_URL = "https://superadmin.sagansa.id"; // Update this with your actual API base URL
    const API_ENDPOINT = "/api/detail-sales-orders";

    // Spreadsheet configuration
    const SHEET_NAME = "Detail Sales Orders";
    const HEADERS = [
        "ID",
        "Product",
        "Sales Order ID",
        "Delivery Date",
        "Quantity",
        "Unit Price",
        "Subtotal",
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
                    "1928|DvkiyXPhc5ixN0kx71TU6dai9jxaK0kIqvh5ggyJ81f4bc25",
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

        const ss = SpreadsheetApp.getActiveSpreadsheet();
        let sheet = ss.getSheetByName(SHEET_NAME);
        if (!sheet) {
            sheet = ss.insertSheet(SHEET_NAME);
        }

        sheet.clear();
        sheet.getRange(1, 1, 1, HEADERS.length).setValues([HEADERS]);
        sheet.getRange(1, 1, 1, HEADERS.length).setFontWeight("bold");

        if (data && data.length > 0) {
            const spreadsheetData = data.map((item) => [
                item.id,
                item.product,
                item.sales_order_id,
                item.delivery_date,
                item.quantity,
                item.unit_price,
                item.subtotal_price,
                item.for,
                item.payment_status,
                item.delivery_status,
                item.store || "N/A",
                item.order_by,
                item.created_at,
                item.updated_at,
            ]);

            sheet
                .getRange(2, 1, spreadsheetData.length, HEADERS.length)
                .setValues(spreadsheetData);
            sheet.autoResizeColumns(1, HEADERS.length);

            // Format date columns only
            const dateColumns = [4, 13, 14];
            dateColumns.forEach((col) => {
                sheet
                    .getRange(2, col, spreadsheetData.length, 1)
                    .setNumberFormat("yyyy-mm-dd hh:mm:ss");
            });

            // Remove currency formatting since we're handling it in the data mapping
            const currencyColumns = [6, 7]; // Unit Price and Subtotal columns
            currencyColumns.forEach((col) => {
                sheet
                    .getRange(2, col, spreadsheetData.length, 1)
                    .setNumberFormat("#,##0.00");
            });
        }

        Logger.log("Data successfully updated");
    } catch (error) {
        Logger.log("Error: " + error.toString());
        throw error;
    }
}

function onOpen() {
    const ui = SpreadsheetApp.getUi();
    ui.createMenu("API Sync")
        .addItem("Sync Detail Sales Orders", "getDetailSalesOrders")
        .addToUi();
}

function getApiToken() {
    const scriptProperties = PropertiesService.getScriptProperties();
    return scriptProperties.getProperty("API_TOKEN");
}
