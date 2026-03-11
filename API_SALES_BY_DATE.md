# API Documentation: Sales By Date (Individual Line Items)

## Endpoint
```
GET /api/detail-sales-orders/sales-by-date
```

## Description
Mengambil **list individual** detail sales orders untuk tanggal tertentu dengan quantity yang sudah dikalikan coefficient.

## Parameters

| Parameter | Type   | Required | Description                                      | Example     |
|-----------|--------|----------|--------------------------------------------------|-------------|
| `date`    | string | Yes      | Tanggal sales (format: YYYY-MM-DD)               | 2026-03-06  |
| `for`     | string | No       | Filter tipe sales order: Direct, Employee, Online | Direct      |

## Response Example

```json
{
  "success": true,
  "data": [
    {
      "detail_sales_order_id": 8454,
      "sales_order_id": 8307,
      "stock_monitoring_id": 3,
      "stock_monitoring_name": "BB3",
      "product_id": 123,
      "product_name": "Mete Menir - BB3",
      "quantity": 1,
      "raw_quantity": 1,
      "coefficient": 1,
      "calculation": "1 × 1 = 1",
      "unit_price": 35000,
      "total_price": 35000,
      "sales_type": "Online",
      "delivery_date": "2026-03-06",
      "store_name": "Palmerah - DGMT",
      "payment_status": "valid",
      "delivery_status": "sudah dikirim"
    },
    {
      "detail_sales_order_id": 8457,
      "sales_order_id": 8310,
      "stock_monitoring_id": 3,
      "stock_monitoring_name": "BB3",
      "product_id": 456,
      "product_name": "Mete Menir - BB3 (10kg)",
      "quantity": 20,
      "raw_quantity": 2,
      "coefficient": 10,
      "calculation": "2 × 10 = 20",
      "unit_price": 3500000,
      "total_price": 7000000,
      "sales_type": "Online",
      "delivery_date": "2026-03-06",
      "store_name": "Palmerah - DGMT",
      "payment_status": "valid",
      "delivery_status": "sudah dikirim"
    }
  ],
  "summary": {
    "date": "2026-03-06",
    "total_items": 23,
    "total_quantity": 111,
    "total_raw_quantity": 30,
    "total_value": 29725000
  }
}
```

## Field Descriptions

### Data Array (Individual Line Items)
| Field                 | Type   | Description                                    |
|-----------------------|--------|------------------------------------------------|
| detail_sales_order_id | int    | ID dari detail sales order                     |
| sales_order_id        | int    | ID dari sales order parent                     |
| stock_monitoring_id   | int    | ID stock monitoring                            |
| stock_monitoring_name | string | Nama stock monitoring (BB3, BB2, dll)          |
| product_id            | int    | ID produk                                      |
| product_name          | string | Nama produk                                    |
| quantity              | int    | Quantity **setelah** dikalikan coefficient     |
| raw_quantity          | int    | Quantity **mentah** dari detail_sales_orders   |
| coefficient           | int    | Coefficient dari stock_monitoring_details      |
| calculation           | string | Rumus: "raw × coef = quantity"                 |
| unit_price            | float  | Harga satuan                                   |
| total_price           | float  | Total harga (sudah × coefficient)              |
| sales_type            | string | Tipe sales: Direct/Employee/Online             |
| delivery_date         | date   | Tanggal pengiriman                             |
| store_name            | string | Nama toko                                      |
| payment_status        | string | Status pembayaran                              |
| delivery_status       | string | Status pengiriman                              |

## Examples

### Get all sales line items for 2026-03-06
```bash
curl -X GET "http://localhost/api/detail-sales-orders/sales-by-date?date=2026-03-06" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Filter by sales type (Direct only)
```bash
curl -X GET "http://localhost/api/detail-sales-orders/sales-by-date?date=2026-03-06&for=Direct" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Calculation Formula

```
quantity = raw_quantity × coefficient
total_price = subtotal_price × coefficient
```

## Sample Output Format

```
SO#8307 | DSO#8454 | BB3 - Mete Menir - BB3 | 1 × 1 = 1 | Rp 35.000 | Online
SO#8310 | DSO#8457 | BB3 - Mete Menir - BB3 (10kg) | 2 × 10 = 20 | Rp 7.000.000 | Online
```

## Notes

1. **Database**: sagansa_2026
2. **Returns**: Individual line items (NOT grouped/summarized)
3. **Clear cache**: `php artisan config:clear && php artisan cache:clear`
