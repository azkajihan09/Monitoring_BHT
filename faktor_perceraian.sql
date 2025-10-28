SELECT fp.nama AS `Faktor Penyebab Perceraian`,

-- Menghitung jumlah pihak laki-laki
SUM(
    CASE
        WHEN pd1.jenis_kelamin = 'L' THEN 1
        ELSE 0
    END
) AS `Laki-laki`,

-- Menghitung jumlah pihak perempuan
SUM(
    CASE
        WHEN pd1.jenis_kelamin = 'P' THEN 1
        ELSE 0
    END
) AS `Perempuan`,

-- Menghitung total keseluruhan
COUNT(*) AS `Jumlah`,

-- Kolom tambahan untuk pengurutan
1 AS `sort_order`
FROM
    perkara p
    LEFT JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
    LEFT JOIN faktor_perceraian fp ON pac.faktor_perceraian_id = fp.id
    LEFT JOIN perkara_pihak1 pp1 ON p.perkara_id = pp1.perkara_id
    LEFT JOIN pihak pd1 ON pp1.pihak_id = pd1.id
WHERE
    YEAR(pac.tgl_akta_cerai) = '2024'
    AND pac.faktor_perceraian_id IS NOT NULL
GROUP BY
    fp.id,
    fp.nama
UNION ALL
SELECT
    'TOTAL' AS `Faktor Penyebab Perceraian`,
    SUM(
        CASE
            WHEN pd1.jenis_kelamin = 'L' THEN 1
            ELSE 0
        END
    ) AS `Laki-laki`,
    SUM(
        CASE
            WHEN pd1.jenis_kelamin = 'P' THEN 1
            ELSE 0
        END
    ) AS `Perempuan`,
    COUNT(*) AS `Jumlah`,
    2 AS `sort_order`
FROM
    perkara p
    LEFT JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
    LEFT JOIN perkara_pihak1 pp1 ON p.perkara_id = pp1.perkara_id
    LEFT JOIN pihak pd1 ON pp1.pihak_id = pd1.id
WHERE
    YEAR(pac.tgl_akta_cerai) = '2024'
    AND pac.faktor_perceraian_id IS NOT NULL
ORDER BY
    `sort_order`,
    `Faktor Penyebab Perceraian`
