SELECT
    fp.nama AS `Faktor Penyebab Perceraian`,
    SUM(
        CASE
            WHEN pd.jenis_kelamin = 'L' THEN 1
            ELSE 0
        END
    ) AS `Jumlah Laki-laki`,
    SUM(
        CASE
            WHEN pd.jenis_kelamin = 'P' THEN 1
            ELSE 0
        END
    ) AS `Jumlah Perempuan`
FROM
    faktor_penyebab fp
    JOIN perkara_pihak1 pp1 ON pp1.faktor_id = fp.id
    JOIN pihak_detail pd ON pd.id = pp1.pihak_id
GROUP BY
    fp.nama
