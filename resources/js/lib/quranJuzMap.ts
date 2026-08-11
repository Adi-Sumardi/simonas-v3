// Static mapping: juz ke { nomor surah, mulai ayat }
// Source: https://en.wikipedia.org/wiki/Juz%27
export interface JuzStart {
    surah: number;
    ayat: number;
}

export const JUZ_START: Record<number, JuzStart> = {
    1:  { surah: 1,   ayat: 1   },
    2:  { surah: 2,   ayat: 142 },
    3:  { surah: 2,   ayat: 253 },
    4:  { surah: 3,   ayat: 92  },
    5:  { surah: 4,   ayat: 24  },
    6:  { surah: 4,   ayat: 148 },
    7:  { surah: 5,   ayat: 82  },
    8:  { surah: 6,   ayat: 111 },
    9:  { surah: 7,   ayat: 88  },
    10: { surah: 8,   ayat: 41  },
    11: { surah: 9,   ayat: 93  },
    12: { surah: 11,  ayat: 6   },
    13: { surah: 12,  ayat: 53  },
    14: { surah: 15,  ayat: 1   },
    15: { surah: 17,  ayat: 1   },
    16: { surah: 18,  ayat: 75  },
    17: { surah: 21,  ayat: 1   },
    18: { surah: 23,  ayat: 1   },
    19: { surah: 25,  ayat: 21  },
    20: { surah: 27,  ayat: 56  },
    21: { surah: 29,  ayat: 46  },
    22: { surah: 33,  ayat: 31  },
    23: { surah: 36,  ayat: 28  },
    24: { surah: 39,  ayat: 32  },
    25: { surah: 41,  ayat: 47  },
    26: { surah: 46,  ayat: 1   },
    27: { surah: 51,  ayat: 31  },
    28: { surah: 58,  ayat: 1   },
    29: { surah: 67,  ayat: 1   },
    30: { surah: 78,  ayat: 1   },
};

/**
 * Get the juz number for a given surah + ayat.
 * Returns the highest juz that starts at or before this position.
 */
export function getJuzNumber(surahNomor: number, ayatNomor: number): number {
    let juz = 1;
    for (let j = 1; j <= 30; j++) {
        const start = JUZ_START[j];
        if (
            start.surah < surahNomor ||
            (start.surah === surahNomor && start.ayat <= ayatNomor)
        ) {
            juz = j;
        } else {
            break;
        }
    }
    return juz;
}

/**
 * Navigate to the first surah/ayat of a given juz.
 */
export function getJuzFirstSurah(juz: number): JuzStart {
    return JUZ_START[juz] ?? { surah: 1, ayat: 1 };
}

/**
 * Get the range of surah numbers that appear in a given juz.
 * A surah is "in" a juz if any of its ayat belong to that juz.
 */
export function getSurahsInJuz(juz: number): { from: number; to: number } {
    const start = JUZ_START[juz];
    const next  = JUZ_START[juz + 1]; // undefined for juz 30

    const from = start?.surah ?? 1;
    // The juz ends just before the next juz starts.
    // If the next juz starts mid-surah (ayat > 1), that surah is still partly in this juz.
    const to = next ? next.surah : 114;

    return { from, to };
}
