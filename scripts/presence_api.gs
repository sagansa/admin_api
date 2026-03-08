function fetchJsonToSheet() {
    var url = "https://superadmin.sagansa.id/api/presences"; // URL API JSON
    var token = "1928|DvkiyXPhc5ixN0kx71TU6dai9jxaK0kIqvh5ggyJ81f4bc25"; // Token API (Jika perlu)

    var options = {
        method: "get",
        headers: {
            Authorization: "Bearer " + token,
            Accept: "application/json",
        },
    };

    var response = UrlFetchApp.fetch(url, options);
    var json = JSON.parse(response.getContentText());

    if (!json.success || !json.data || json.data.length === 0) {
        Logger.log("⚠️ Tidak ada data yang ditemukan!");
        return;
    }

    var sheet =
        SpreadsheetApp.getActiveSpreadsheet().getSheetByName("presences");
    if (!sheet) {
        Logger.log("⚠️ Sheet 'presences' tidak ditemukan!");
        return;
    }

    var lastRow = sheet.getLastRow();
    var headers = [
        "ID",
        "Creator",
        "Store",
        "Shift Name",
        "Shift Start",
        "Shift End",
        "Shift Duration",
        "Check In",
        "Check Out",
        "created_at",
        "updated_at",
        "Last Sync",
    ];

    // Jika sheet kosong, tambahkan header terlebih dahulu
    if (lastRow === 0) {
        sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
        lastRow = 1;
    }

    var existingData =
        lastRow > 1
            ? sheet.getRange(2, 1, lastRow - 1, headers.length).getValues()
            : [];
    var existingIds = {}; // Menyimpan ID, updated_at, dan last_sync untuk membandingkan perubahan

    existingData.forEach((row) => {
        existingIds[row[0]] = {
            updated_at: row[10],
            last_sync: row[11],
        };
    });

    var newValues = [];

    json.data.forEach((row) => {
        var id = row.id;
        var updatedAt = row.updated_at;
        var createdAt = row.created_at;

        // Jika ID belum ada atau data lebih baru dari last sync, tambahkan/overwrite data
        if (
            !existingIds[id] ||
            !existingIds[id].last_sync ||
            new Date(updatedAt) > new Date(existingIds[id].last_sync) ||
            new Date(createdAt) > new Date(existingIds[id].last_sync)
        ) {
            newValues.push([
                row.id,
                row.creator,
                row.store,
                row.shift_name,
                row.shift_start_time,
                row.shift_end_time,
                row.shift_duration,
                row.check_in,
                row.check_out,
                row.created_at,
                row.updated_at,
                new Date().toISOString(),
            ]);
        }
    });

    // **🛠 FIX: Cek apakah newValues memiliki data sebelum setValues()**
    if (newValues.length > 0) {
        sheet
            .getRange(lastRow + 1, 1, newValues.length, headers.length)
            .setValues(newValues);
        Logger.log(
            "✅ " +
                newValues.length +
                " data baru diperbarui/dimasukkan ke sheet 'presences'!"
        );
    } else {
        Logger.log("ℹ️ Tidak ada data baru yang perlu disinkronkan.");
    }
}
