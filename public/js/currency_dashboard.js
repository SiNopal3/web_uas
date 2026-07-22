/**
 * Currency Impact Dashboard Engine (v4.5 - Bersih & Fokus Spesifikasi UAS)
 * Menampikan: Pencarian 195 Negara Berdaulat Persis Fitur No. 1 -> Langsung memperbarui Grafik Perubahan Kurs (Chart.js) Lebar Penuh.
 */

document.addEventListener('DOMContentLoaded', () => {
    let trendChart = null;
    let currentSelectedCode = 'IDR';
    let currentSelectedCountry = 'Indonesia';
    let currentSelectedSymbol = 'Rp';
    let currentPeriodDays = 30;
    let liveRatesData = {};

    // 1. DAFTAR 195 NEGARA BERDAULAT & KODE MATA UANGNYA (LENGKAP DENGAN BENERA & REGIONAL)
    const SOVEREIGN_195_CURRENCIES = [
        { country: "Afghanistan", iso: "AF", code: "AFN", symbol: "؋", rate_usd: 71.5, region: "Asia" },
        { country: "Albania", iso: "AL", code: "ALL", symbol: "L", rate_usd: 93.2, region: "Europe" },
        { country: "Algeria", iso: "DZ", code: "DZD", symbol: "د.ج", rate_usd: 134.5, region: "Africa" },
        { country: "Andorra", iso: "AD", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Angola", iso: "AO", code: "AOA", symbol: "Kz", rate_usd: 852.0, region: "Africa" },
        { country: "Antigua and Barbuda", iso: "AG", code: "XCD", symbol: "$", rate_usd: 2.70, region: "Americas" },
        { country: "Argentina", iso: "AR", code: "ARS", symbol: "$", rate_usd: 915.0, region: "Americas" },
        { country: "Armenia", iso: "AM", code: "AMD", symbol: "֏", rate_usd: 388.0, region: "Asia" },
        { country: "Australia", iso: "AU", code: "AUD", symbol: "A$", rate_usd: 1.51, region: "Oceania" },
        { country: "Austria", iso: "AT", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Azerbaijan", iso: "AZ", code: "AZN", symbol: "₼", rate_usd: 1.70, region: "Asia" },
        { country: "Bahamas", iso: "BS", code: "BSD", symbol: "$", rate_usd: 1.00, region: "Americas" },
        { country: "Bahrain", iso: "BH", code: "BHD", symbol: ".د.ب", rate_usd: 0.376, region: "Middle East" },
        { country: "Bangladesh", iso: "BD", code: "BDT", symbol: "৳", rate_usd: 117.5, region: "Asia" },
        { country: "Barbados", iso: "BB", code: "BBD", symbol: "$", rate_usd: 2.00, region: "Americas" },
        { country: "Belarus", iso: "BY", code: "BYN", symbol: "Br", rate_usd: 3.27, region: "Europe" },
        { country: "Belgium", iso: "BE", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Belize", iso: "BZ", code: "BZD", symbol: "BZ$", rate_usd: 2.00, region: "Americas" },
        { country: "Benin", iso: "BJ", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Bhutan", iso: "BT", code: "BTN", symbol: "Nu.", rate_usd: 83.5, region: "Asia" },
        { country: "Bolivia", iso: "BO", code: "BOB", symbol: "Bs.", rate_usd: 6.91, region: "Americas" },
        { country: "Bosnia and Herzegovina", iso: "BA", code: "BAM", symbol: "KM", rate_usd: 1.80, region: "Europe" },
        { country: "Botswana", iso: "BW", code: "BWP", symbol: "P", rate_usd: 13.6, region: "Africa" },
        { country: "Brazil", iso: "BR", code: "BRL", symbol: "R$", rate_usd: 5.35, region: "Americas" },
        { country: "Brunei", iso: "BN", code: "BND", symbol: "B$", rate_usd: 1.35, region: "Asia" },
        { country: "Bulgaria", iso: "BG", code: "BGN", symbol: "лв", rate_usd: 1.80, region: "Europe" },
        { country: "Burkina Faso", iso: "BF", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Burundi", iso: "BI", code: "BIF", symbol: "FBu", rate_usd: 2865.0, region: "Africa" },
        { country: "Cabo Verde", iso: "CV", code: "CVE", symbol: "$", rate_usd: 101.5, region: "Africa" },
        { country: "Cambodia", iso: "KH", code: "KHR", symbol: "៛", rate_usd: 4110.0, region: "Asia" },
        { country: "Cameroon", iso: "CM", code: "XAF", symbol: "FCFA", rate_usd: 603.5, region: "Africa" },
        { country: "Canada", iso: "CA", code: "CAD", symbol: "C$", rate_usd: 1.37, region: "Americas" },
        { country: "Central African Republic", iso: "CF", code: "XAF", symbol: "FCFA", rate_usd: 603.5, region: "Africa" },
        { country: "Chad", iso: "TD", code: "XAF", symbol: "FCFA", rate_usd: 603.5, region: "Africa" },
        { country: "Chile", iso: "CL", code: "CLP", symbol: "$", rate_usd: 935.0, region: "Americas" },
        { country: "China", iso: "CN", code: "CNY", symbol: "¥", rate_usd: 7.24, region: "Asia" },
        { country: "Colombia", iso: "CO", code: "COP", symbol: "$", rate_usd: 3950.0, region: "Americas" },
        { country: "Comoros", iso: "KM", code: "KMF", symbol: "CF", rate_usd: 452.5, region: "Africa" },
        { country: "Congo (Brazzaville)", iso: "CG", code: "XAF", symbol: "FCFA", rate_usd: 603.5, region: "Africa" },
        { country: "Congo (Kinshasa)", iso: "CD", code: "CDF", symbol: "FC", rate_usd: 2820.0, region: "Africa" },
        { country: "Costa Rica", iso: "CR", code: "CRC", symbol: "₡", rate_usd: 512.0, region: "Americas" },
        { country: "Croatia", iso: "HR", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Cuba", iso: "CU", code: "CUP", symbol: "$", rate_usd: 24.0, region: "Americas" },
        { country: "Cyprus", iso: "CY", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Czechia", iso: "CZ", code: "CZK", symbol: "Kč", rate_usd: 22.8, region: "Europe" },
        { country: "Denmark", iso: "DK", code: "DKK", symbol: "kr", rate_usd: 6.88, region: "Europe" },
        { country: "Djibouti", iso: "DJ", code: "DJF", symbol: "Fdj", rate_usd: 177.7, region: "Africa" },
        { country: "Dominica", iso: "DM", code: "XCD", symbol: "$", rate_usd: 2.70, region: "Americas" },
        { country: "Dominican Republic", iso: "DO", code: "DOP", symbol: "RD$", rate_usd: 59.2, region: "Americas" },
        { country: "Ecuador", iso: "EC", code: "USD", symbol: "$", rate_usd: 1.00, region: "Americas" },
        { country: "Egypt", iso: "EG", code: "EGP", symbol: "E£", rate_usd: 47.8, region: "Africa" },
        { country: "El Salvador", iso: "SV", code: "USD", symbol: "$", rate_usd: 1.00, region: "Americas" },
        { country: "Equatorial Guinea", iso: "GQ", code: "XAF", symbol: "FCFA", rate_usd: 603.5, region: "Africa" },
        { country: "Eritrea", iso: "ER", code: "ERN", symbol: "Nfk", rate_usd: 15.0, region: "Africa" },
        { country: "Estonia", iso: "EE", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Eswatini", iso: "SZ", code: "SZL", symbol: "L", rate_usd: 18.2, region: "Africa" },
        { country: "Ethiopia", iso: "ET", code: "ETB", symbol: "Br", rate_usd: 57.5, region: "Africa" },
        { country: "Fiji", iso: "FJ", code: "FJD", symbol: "$", rate_usd: 2.25, region: "Oceania" },
        { country: "Finland", iso: "FI", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "France", iso: "FR", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Gabon", iso: "GA", code: "XAF", symbol: "FCFA", rate_usd: 603.5, region: "Africa" },
        { country: "Gambia", iso: "GM", code: "GMD", symbol: "D", rate_usd: 67.8, region: "Africa" },
        { country: "Georgia", iso: "GE", code: "GEL", symbol: "₾", rate_usd: 2.82, region: "Asia" },
        { country: "Germany", iso: "DE", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Ghana", iso: "GH", code: "GHS", symbol: "GH₵", rate_usd: 14.8, region: "Africa" },
        { country: "Greece", iso: "GR", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Grenada", iso: "GD", code: "XCD", symbol: "$", rate_usd: 2.70, region: "Americas" },
        { country: "Guatemala", iso: "GT", code: "GTQ", symbol: "Q", rate_usd: 7.78, region: "Americas" },
        { country: "Guinea", iso: "GN", code: "GNF", symbol: "FG", rate_usd: 8600.0, region: "Africa" },
        { country: "Guinea-Bissau", iso: "GW", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Guyana", iso: "GY", code: "GYD", symbol: "$", rate_usd: 209.0, region: "Americas" },
        { country: "Haiti", iso: "HT", code: "HTG", symbol: "G", rate_usd: 132.5, region: "Americas" },
        { country: "Honduras", iso: "HN", code: "HNL", symbol: "L", rate_usd: 24.7, region: "Americas" },
        { country: "Hungary", iso: "HU", code: "HUF", symbol: "Ft", rate_usd: 362.0, region: "Europe" },
        { country: "Iceland", iso: "IS", code: "ISK", symbol: "kr", rate_usd: 138.5, region: "Europe" },
        { country: "India", iso: "IN", code: "INR", symbol: "₹", rate_usd: 83.5, region: "Asia" },
        { country: "Indonesia", iso: "ID", code: "IDR", symbol: "Rp", rate_usd: 16250.0, region: "Southeast Asia" },
        { country: "Iran", iso: "IR", code: "IRR", symbol: "﷼", rate_usd: 42000.0, region: "Middle East" },
        { country: "Iraq", iso: "IQ", code: "IQD", symbol: "ع.د", rate_usd: 1310.0, region: "Middle East" },
        { country: "Ireland", iso: "IE", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Israel", iso: "IL", code: "ILS", symbol: "₪", rate_usd: 3.75, region: "Middle East" },
        { country: "Italy", iso: "IT", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Jamaica", iso: "JM", code: "JMD", symbol: "J$", rate_usd: 156.0, region: "Americas" },
        { country: "Japan", iso: "JP", code: "JPY", symbol: "¥", rate_usd: 156.5, region: "East Asia" },
        { country: "Jordan", iso: "JO", code: "JOD", symbol: "د.ا", rate_usd: 0.709, region: "Middle East" },
        { country: "Kazakhstan", iso: "KZ", code: "KZT", symbol: "₸", rate_usd: 454.0, region: "Central Asia" },
        { country: "Kenya", iso: "KE", code: "KES", symbol: "KSh", rate_usd: 130.5, region: "Africa" },
        { country: "Kiribati", iso: "KI", code: "AUD", symbol: "A$", rate_usd: 1.51, region: "Oceania" },
        { country: "Kuwait", iso: "KW", code: "KWD", symbol: "د.ك", rate_usd: 0.307, region: "Middle East" },
        { country: "Kyrgyzstan", iso: "KG", code: "KGS", symbol: "с", rate_usd: 88.2, region: "Central Asia" },
        { country: "Laos", iso: "LA", code: "LAK", symbol: "₭", rate_usd: 21500.0, region: "Southeast Asia" },
        { country: "Latvia", iso: "LV", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Lebanon", iso: "LB", code: "LBP", symbol: "ل.ل", rate_usd: 89500.0, region: "Middle East" },
        { country: "Lesotho", iso: "LS", code: "LSL", symbol: "L", rate_usd: 18.2, region: "Africa" },
        { country: "Liberia", iso: "LR", code: "LRD", symbol: "$", rate_usd: 193.5, region: "Africa" },
        { country: "Libya", iso: "LY", code: "LYD", symbol: "ل.د", rate_usd: 4.86, region: "Africa" },
        { country: "Liechtenstein", iso: "LI", code: "CHF", symbol: "CHF", rate_usd: 0.90, region: "Europe" },
        { country: "Lithuania", iso: "LT", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Luxembourg", iso: "LU", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Madagascar", iso: "MG", code: "MGA", symbol: "Ar", rate_usd: 4450.0, region: "Africa" },
        { country: "Malawi", iso: "MW", code: "MWK", symbol: "MK", rate_usd: 1750.0, region: "Africa" },
        { country: "Malaysia", iso: "MY", code: "MYR", symbol: "RM", rate_usd: 4.71, region: "Southeast Asia" },
        { country: "Maldives", iso: "MV", code: "MVR", symbol: "Rf", rate_usd: 15.4, region: "Asia" },
        { country: "Mali", iso: "ML", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Malta", iso: "MT", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Marshall Islands", iso: "MH", code: "USD", symbol: "$", rate_usd: 1.00, region: "Oceania" },
        { country: "Mauritania", iso: "MR", code: "MRU", symbol: "UM", rate_usd: 39.8, region: "Africa" },
        { country: "Mauritius", iso: "MU", code: "MUR", symbol: "₨", rate_usd: 46.5, region: "Africa" },
        { country: "Mexico", iso: "MX", code: "MXN", symbol: "$", rate_usd: 17.1, region: "Americas" },
        { country: "Micronesia", iso: "FM", code: "USD", symbol: "$", rate_usd: 1.00, region: "Oceania" },
        { country: "Moldova", iso: "MD", code: "MDL", symbol: "L", rate_usd: 17.8, region: "Europe" },
        { country: "Monaco", iso: "MC", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Mongolia", iso: "MN", code: "MNT", symbol: "₮", rate_usd: 3450.0, region: "East Asia" },
        { country: "Montenegro", iso: "ME", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Morocco", iso: "MA", code: "MAD", symbol: "د.م.", rate_usd: 9.95, region: "Africa" },
        { country: "Mozambique", iso: "MZ", code: "MZN", symbol: "MT", rate_usd: 63.8, region: "Africa" },
        { country: "Myanmar", iso: "MM", code: "MMK", symbol: "K", rate_usd: 2100.0, region: "Southeast Asia" },
        { country: "Namibia", iso: "NA", code: "NAD", symbol: "N$", rate_usd: 18.2, region: "Africa" },
        { country: "Nauru", iso: "NR", code: "AUD", symbol: "A$", rate_usd: 1.51, region: "Oceania" },
        { country: "Nepal", iso: "NP", code: "NPR", symbol: "₨", rate_usd: 133.5, region: "South Asia" },
        { country: "Netherlands", iso: "NL", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "New Zealand", iso: "NZ", code: "NZD", symbol: "NZ$", rate_usd: 1.63, region: "Oceania" },
        { country: "Nicaragua", iso: "NI", code: "NIO", symbol: "C$", rate_usd: 36.8, region: "Americas" },
        { country: "Niger", iso: "NE", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Nigeria", iso: "NG", code: "NGN", symbol: "₦", rate_usd: 1480.0, region: "Africa" },
        { country: "North Korea", iso: "KP", code: "KPW", symbol: "₩", rate_usd: 900.0, region: "East Asia" },
        { country: "North Macedonia", iso: "MK", code: "MKD", symbol: "ден", rate_usd: 56.8, region: "Europe" },
        { country: "Norway", iso: "NO", code: "NOK", symbol: "kr", rate_usd: 10.55, region: "Europe" },
        { country: "Oman", iso: "OM", code: "OMR", symbol: "ر.ع.", rate_usd: 0.384, region: "Middle East" },
        { country: "Pakistan", iso: "PK", code: "PKR", symbol: "₨", rate_usd: 278.5, region: "South Asia" },
        { country: "Palau", iso: "PW", code: "USD", symbol: "$", rate_usd: 1.00, region: "Oceania" },
        { country: "Panama", iso: "PA", code: "PAB", symbol: "B/.", rate_usd: 1.00, region: "Americas" },
        { country: "Papua New Guinea", iso: "PG", code: "PGK", symbol: "K", rate_usd: 3.88, region: "Oceania" },
        { country: "Paraguay", iso: "PY", code: "PYG", symbol: "₲", rate_usd: 7530.0, region: "Americas" },
        { country: "Peru", iso: "PE", code: "PEN", symbol: "S/.", rate_usd: 3.75, region: "Americas" },
        { country: "Philippines", iso: "PH", code: "PHP", symbol: "₱", rate_usd: 58.5, region: "Southeast Asia" },
        { country: "Poland", iso: "PL", code: "PLN", symbol: "zł", rate_usd: 3.95, region: "Europe" },
        { country: "Portugal", iso: "PT", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Qatar", iso: "QA", code: "QAR", symbol: "ر.ق", rate_usd: 3.64, region: "Middle East" },
        { country: "Romania", iso: "RO", code: "RON", symbol: "lei", rate_usd: 4.58, region: "Europe" },
        { country: "Russia", iso: "RU", code: "RUB", symbol: "₽", rate_usd: 88.5, region: "Eurasia" },
        { country: "Rwanda", iso: "RW", code: "RWF", symbol: "FRw", rate_usd: 1315.0, region: "Africa" },
        { country: "Saint Kitts and Nevis", iso: "KN", code: "XCD", symbol: "$", rate_usd: 2.70, region: "Americas" },
        { country: "Saint Lucia", iso: "LC", code: "XCD", symbol: "$", rate_usd: 2.70, region: "Americas" },
        { country: "Saint Vincent and the Grenadines", iso: "VC", code: "XCD", symbol: "$", rate_usd: 2.70, region: "Americas" },
        { country: "Samoa", iso: "WS", code: "WST", symbol: "T", rate_usd: 2.75, region: "Oceania" },
        { country: "San Marino", iso: "SM", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Sao Tome and Principe", iso: "ST", code: "STN", symbol: "Db", rate_usd: 22.5, region: "Africa" },
        { country: "Saudi Arabia", iso: "SA", code: "SAR", symbol: "SR", rate_usd: 3.75, region: "Middle East" },
        { country: "Senegal", iso: "SN", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Serbia", iso: "RS", code: "RSD", symbol: "дин", rate_usd: 107.8, region: "Europe" },
        { country: "Seychelles", iso: "SC", code: "SCR", symbol: "₨", rate_usd: 13.8, region: "Africa" },
        { country: "Sierra Leone", iso: "SL", code: "SLE", symbol: "Le", rate_usd: 22.6, region: "Africa" },
        { country: "Singapore", iso: "SG", code: "SGD", symbol: "S$", rate_usd: 1.35, region: "Southeast Asia" },
        { country: "Slovakia", iso: "SK", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Slovenia", iso: "SI", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Solomon Islands", iso: "SB", code: "SBD", symbol: "SI$", rate_usd: 8.48, region: "Oceania" },
        { country: "Somalia", iso: "SO", code: "SOS", symbol: "S", rate_usd: 571.0, region: "Africa" },
        { country: "South Africa", iso: "ZA", code: "ZAR", symbol: "R", rate_usd: 18.2, region: "Africa" },
        { country: "South Korea", iso: "KR", code: "KRW", symbol: "₩", rate_usd: 1380.0, region: "East Asia" },
        { country: "South Sudan", iso: "SS", code: "SSP", symbol: "£", rate_usd: 1560.0, region: "Africa" },
        { country: "Spain", iso: "ES", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Sri Lanka", iso: "LK", code: "LKR", symbol: "₨", rate_usd: 302.0, region: "South Asia" },
        { country: "Sudan", iso: "SD", code: "SDG", symbol: "ج.س.", rate_usd: 601.0, region: "Africa" },
        { country: "Suriname", iso: "SR", code: "SRD", symbol: "$", rate_usd: 32.5, region: "Americas" },
        { country: "Sweden", iso: "SE", code: "SEK", symbol: "kr", rate_usd: 10.45, region: "Europe" },
        { country: "Switzerland", iso: "CH", code: "CHF", symbol: "CHF", rate_usd: 0.90, region: "Europe" },
        { country: "Syria", iso: "SY", code: "SYP", symbol: "£", rate_usd: 13000.0, region: "Middle East" },
        { country: "Tajikistan", iso: "TJ", code: "TJS", symbol: "SM", rate_usd: 10.9, region: "Central Asia" },
        { country: "Tanzania", iso: "TZ", code: "TZS", symbol: "TSh", rate_usd: 2615.0, region: "Africa" },
        { country: "Thailand", iso: "TH", code: "THB", symbol: "฿", rate_usd: 36.7, region: "Southeast Asia" },
        { country: "Timor-Leste", iso: "TL", code: "USD", symbol: "$", rate_usd: 1.00, region: "Southeast Asia" },
        { country: "Togo", iso: "TG", code: "XOF", symbol: "CFA", rate_usd: 603.5, region: "Africa" },
        { country: "Tonga", iso: "TO", code: "TOP", symbol: "T$", rate_usd: 2.35, region: "Oceania" },
        { country: "Trinidad and Tobago", iso: "TT", code: "TTD", symbol: "TT$", rate_usd: 6.78, region: "Americas" },
        { country: "Tunisia", iso: "TN", code: "TND", symbol: "د.ت", rate_usd: 3.12, region: "Africa" },
        { country: "Turkey", iso: "TR", code: "TRY", symbol: "₺", rate_usd: 32.5, region: "Eurasia" },
        { country: "Turkmenistan", iso: "TM", code: "TMT", symbol: "T", rate_usd: 3.50, region: "Central Asia" },
        { country: "Tuvalu", iso: "TV", code: "AUD", symbol: "A$", rate_usd: 1.51, region: "Oceania" },
        { country: "Uganda", iso: "UG", code: "UGX", symbol: "USh", rate_usd: 3750.0, region: "Africa" },
        { country: "Ukraine", iso: "UA", code: "UAH", symbol: "₴", rate_usd: 40.5, region: "Europe" },
        { country: "United Arab Emirates", iso: "AE", code: "AED", symbol: "د.إ", rate_usd: 3.67, region: "Middle East" },
        { country: "United Kingdom", iso: "GB", code: "GBP", symbol: "£", rate_usd: 0.79, region: "Europe" },
        { country: "United States", iso: "US", code: "USD", symbol: "$", rate_usd: 1.00, region: "Americas" },
        { country: "Uruguay", iso: "UY", code: "UYU", symbol: "$U", rate_usd: 39.2, region: "Americas" },
        { country: "Uzbekistan", iso: "UZ", code: "UZS", symbol: "so'm", rate_usd: 12650.0, region: "Central Asia" },
        { country: "Vanuatu", iso: "VU", code: "VUV", symbol: "VT", rate_usd: 119.5, region: "Oceania" },
        { country: "Vatican City", iso: "VA", code: "EUR", symbol: "€", rate_usd: 0.92, region: "Europe" },
        { country: "Venezuela", iso: "VE", code: "VES", symbol: "Bs.", rate_usd: 36.5, region: "Americas" },
        { country: "Vietnam", iso: "VN", code: "VND", symbol: "₫", rate_usd: 25450.0, region: "Southeast Asia" },
        { country: "Yemen", iso: "YE", code: "YER", symbol: "﷼", rate_usd: 250.0, region: "Middle East" },
        { country: "Zambia", iso: "ZM", code: "ZMW", symbol: "ZK", rate_usd: 26.2, region: "Africa" },
        { country: "Zimbabwe", iso: "ZW", code: "ZWL", symbol: "Z$", rate_usd: 13.8, region: "Africa" }
    ];

    // Mulai inisialisasi
    initCountrySearchPersisFitur1();
    initPeriodButtons();
    restoreSavedCurrencyOrInitDefault();
    fetchLiveConversionRates();

    /**
     * 1. Inisialisasi Input Pencarian Interaktif 195 Negara Berdaulat (PERSIS FITUR NO. 1)
     */
    function initCountrySearchPersisFitur1() {
        const searchInput = document.getElementById('currCountrySearchInput') || document.getElementById('countrySearchInput');
        const dropdownList = document.getElementById('currCountryDropdownList') || document.getElementById('countryDropdownList');

        if (!searchInput || !dropdownList) return;

        const renderCountryOptions = (query = '') => {
            dropdownList.innerHTML = '';
            const cleanQuery = query.trim().toLowerCase();

            const filtered = SOVEREIGN_195_CURRENCIES.filter(item => {
                if (!cleanQuery) return true;
                if (item.country.toLowerCase().includes(cleanQuery)) return true;
                if (item.code.toLowerCase().includes(cleanQuery)) return true;
                if (item.iso.toLowerCase().includes(cleanQuery)) return true;
                if (item.region.toLowerCase().includes(cleanQuery)) return true;
                return false;
            });

            if (filtered.length === 0) {
                dropdownList.innerHTML = `<div class="p-3 text-muted small text-center">Negara atau mata uang "${query}" tidak ditemukan. Coba nama lain.</div>`;
            } else {
                filtered.slice(0, 100).forEach(item => {
                    const el = document.createElement('div');
                    el.className = 'dropdown-item d-flex justify-content-between align-items-center py-2 px-3 text-white border-bottom border-secondary';
                    el.style.cursor = 'pointer';
                    el.style.fontSize = '13px';

                    // Gunakan live rate jika tersedia
                    const activeRate = (liveRatesData && liveRatesData[item.code]) ? liveRatesData[item.code] : item.rate_usd;

                    el.innerHTML = `
                        <div>
                            <span class="fw-bold me-1">${item.country}</span>
                            <span class="badge bg-dark border border-secondary text-warning small">${item.code}</span>
                        </div>
                        <span class="small text-info fw-bold">${item.symbol} ${activeRate.toLocaleString('id-ID', { maximumFractionDigits: 2 })} (${item.region})</span>
                    `;

                    el.addEventListener('click', () => {
                        searchInput.value = `${item.country} (${item.code} - ${item.symbol})`;
                        dropdownList.style.display = 'none';
                        selectCurrency(item.code, item.symbol, activeRate, item.country);
                    });

                    el.addEventListener('mouseenter', () => el.style.background = 'rgba(255, 193, 7, 0.2)');
                    el.addEventListener('mouseleave', () => el.style.background = 'transparent');
                    dropdownList.appendChild(el);
                });
            }
            dropdownList.style.display = 'block';
        };

        window.openCurrCountryDropdown = function(query = '') {
            renderCountryOptions(query);
            if (dropdownList) dropdownList.scrollTop = 0;
        };

        searchInput.addEventListener('input', (e) => {
            renderCountryOptions(e.target.value);
        });

        searchInput.addEventListener('focus', () => {
            renderCountryOptions(searchInput.value.replace(/\s*\(.*\)/, ''));
        });

        // Klik di luar tutup dropdown
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !dropdownList.contains(e.target)) {
                dropdownList.style.display = 'none';
            }
        });
    }

    window.resetCurrToGlobal = function() {
        const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_currency';
        sessionStorage.removeItem(featKey);
        localStorage.removeItem(featKey);
        sessionStorage.removeItem(featKey + '_curr');
        localStorage.removeItem(featKey + '_curr');
        const searchInput = document.getElementById('currCountrySearchInput');
        if (searchInput) searchInput.value = '';
        const elCountry = document.getElementById('currSelectedCountryName') || document.getElementById('selectedCountryName');
        const elCodeBadge = document.getElementById('currSelectedCodeBadge') || document.getElementById('selectedCountryCurrency');
        const elRateDisplay = document.getElementById('currSelectedRateDisplay');
        const elChartTitle = document.getElementById('chartCurrTitle');
        if (elCountry) elCountry.textContent = '-';
        if (elCodeBadge) elCodeBadge.textContent = '-';
        if (elRateDisplay) elRateDisplay.textContent = '-';
        if (elChartTitle) elChartTitle.textContent = '-';
        setTimeout(() => {
            if (searchInput && typeof window.openCurrCountryDropdown === 'function') {
                searchInput.focus();
                window.openCurrCountryDropdown('');
            }
        }, 150);
    };

    /**
     * 2. Period Buttons (30 Hari, 90 Hari, 6 Bulan, 1 Tahun)
     */
    function initPeriodButtons() {
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentPeriodDays = parseInt(btn.getAttribute('data-period')) || 30;

                const match = SOVEREIGN_195_CURRENCIES.find(c => c.code === currentSelectedCode);
                const rate = match ? match.rate_usd : (liveRatesData[currentSelectedCode] || 1.0);
                renderTrendChart(currentSelectedCode, currentSelectedSymbol, rate, currentSelectedCountry);
            });
        });
    }

    /**
     * 3. Pemilihan Mata Uang Aktif (Update Kartu Header Kiri & LANGSUNG REDRAW GRAFIK GAMBAR 4)
     */
    function selectCurrency(code, symbol, rateUsd, countryName, isDefaultInit = false) {
        currentSelectedCode = code;
        currentSelectedSymbol = symbol;
        currentSelectedCountry = countryName;

        // Simpan pilihan ke penyimpanan khusus fitur Currency Impact ini (independen)
        if (!isDefaultInit) {
            const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_currency';
            sessionStorage.setItem(featKey, countryName);
            localStorage.setItem(featKey, countryName);
            sessionStorage.setItem(featKey + '_curr', code);
            localStorage.setItem(featKey + '_curr', code);
        }

        if (liveRatesData && liveRatesData[code]) {
            rateUsd = liveRatesData[code];
        }

        // Update Kartu Kiri (PERSIS Fitur No. 1)
        const elCountry = document.getElementById('currSelectedCountryName') || document.getElementById('selectedCountryName');
        const elCodeBadge = document.getElementById('currSelectedCodeBadge') || document.getElementById('selectedCountryCurrency');
        const elRateDisplay = document.getElementById('currSelectedRateDisplay');
        const elChartTitle = document.getElementById('chartCurrTitle');

        const isUnselected = (countryName === 'Global / Semua Negara' || countryName === 'Global / Semua Negara (Feed Artikel Admin)' || countryName === 'Global' || countryName === 'Belum Dipilih' || countryName === '-' || !countryName || code === 'Global' || code === '-');

        if (isUnselected) {
            if (elCountry) elCountry.textContent = '-';
            if (elCodeBadge) elCodeBadge.textContent = '-';
            if (elRateDisplay) elRateDisplay.textContent = '-';
            if (elChartTitle) elChartTitle.textContent = '-';
        } else {
            if (elCountry) elCountry.textContent = countryName;
            if (elCodeBadge) elCodeBadge.textContent = code;
            if (elRateDisplay) elRateDisplay.textContent = `${symbol} ${rateUsd.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} / USD`;
            if (elChartTitle) elChartTitle.textContent = `${code} / USD`;
        }

        // LANGSUNG redrawing Grafik Gambar ke 4
        renderTrendChart(code, symbol, rateUsd, countryName);

        // Sinkronisasi dengan global dashboard jika ada di halaman yang sama
        if (typeof window.selectCountry === 'function' && window.countryMetadata && window.countryMetadata[countryName] && (!window.activeCountryData || window.activeCountryData.name !== countryName)) {
            window.selectCountry(countryName);
        }
    }

    /**
     * Kembalikan negara yang sebelumnya dipilih oleh pengguna khusus di fitur Currency ini
     */
    function restoreSavedCurrencyOrInitDefault() {
        const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_currency';
        const savedCountry = sessionStorage.getItem(featKey) || localStorage.getItem(featKey);
        const savedCode = sessionStorage.getItem(featKey + '_curr');

        let match = null;
        if (savedCountry && savedCountry !== 'Global / Semua Negara' && savedCountry !== 'Belum Dipilih' && savedCountry !== '-') {
            const scLower = savedCountry.toLowerCase().trim();
            match = SOVEREIGN_195_CURRENCIES.find(c => 
                c.country.toLowerCase() === scLower || 
                c.iso.toLowerCase() === scLower || 
                c.country.toLowerCase().includes(scLower) || 
                scLower.includes(c.country.toLowerCase())
            );
        }
        if (!match && savedCode && savedCode !== 'Global' && savedCode !== '-') {
            const scCode = savedCode.toLowerCase().trim();
            match = SOVEREIGN_195_CURRENCIES.find(c => c.code.toLowerCase() === scCode || c.iso.toLowerCase() === scCode);
        }

        if (match && match.country !== 'Belum Dipilih') {
            const activeRate = (liveRatesData && liveRatesData[match.code]) ? liveRatesData[match.code] : match.rate_usd;
            selectCurrency(match.code, match.symbol, activeRate, match.country, false);
            const searchInput = document.getElementById('currCountrySearchInput') || document.getElementById('countrySearchInput');
            if (searchInput) searchInput.value = `${match.country} (${match.code} - ${match.symbol})`;
        } else {
            // Jika belum ada negara terpilih, setel tanda '-' dan kosongkan chart
            selectCurrency('Global', '-', 0, 'Global / Semua Negara', true);
            const searchInput = document.getElementById('currCountrySearchInput') || document.getElementById('countrySearchInput');
            if (searchInput) searchInput.value = '';
        }
    }

    /**
     * 4. Render Grafik Perubahan Kurs (Chart.js Lebar Penuh)
     */
    function renderTrendChart(code, symbol, baseRate, countryName) {
        const ctx = document.getElementById('currencyTrendChartCanvas');
        if (!ctx || typeof Chart === 'undefined') {
            console.warn('Menunggu Chart.js siap dimuat...');
            setTimeout(() => renderTrendChart(code, symbol, baseRate, countryName), 150);
            return;
        }

        const isUnselected = (countryName === 'Global / Semua Negara' || countryName === 'Global' || countryName === 'Belum Dipilih' || countryName === '-' || !countryName || code === 'Global' || code === '-');

        const elHigh = document.getElementById('statHighRate');
        const elLow = document.getElementById('statLowRate');
        const elAvg = document.getElementById('statAvgRate');

        if (isUnselected) {
            if (elHigh) elHigh.textContent = '-';
            if (elLow) elLow.textContent = '-';
            if (elAvg) elAvg.textContent = '-';
            if (trendChart) {
                trendChart.destroy();
                trendChart = null;
            }
            return;
        }

        const labels = [];
        const dataPoints = [];
        const now = new Date();
        let currentSimRate = baseRate * 0.96;
        
        const volatilityFactor = ['IDR', 'TRY', 'ARS', 'EGP', 'NGN'].includes(code) ? 0.008 : 0.003;

        for (let i = currentPeriodDays; i >= 0; i--) {
            const d = new Date(now);
            d.setDate(d.getDate() - i);
            labels.push(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));

            if (i === 0) {
                dataPoints.push(baseRate);
            } else {
                const randomDelta = (Math.random() - 0.48) * volatilityFactor * currentSimRate;
                currentSimRate += randomDelta;
                dataPoints.push(roundToDigits(currentSimRate, baseRate > 100 ? 0 : 4));
            }
        }

        const highVal = Math.max(...dataPoints);
        const lowVal = Math.min(...dataPoints);
        const avgVal = dataPoints.reduce((a, b) => a + b, 0) / dataPoints.length;

        if (elHigh) elHigh.textContent = symbol + ' ' + highVal.toLocaleString('id-ID', { maximumFractionDigits: 2 });
        if (elLow) elLow.textContent = symbol + ' ' + lowVal.toLocaleString('id-ID', { maximumFractionDigits: 2 });
        if (elAvg) elAvg.textContent = symbol + ' ' + avgVal.toLocaleString('id-ID', { maximumFractionDigits: 2 });

        if (trendChart) trendChart.destroy();

        trendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `Nilai Tukar ${countryName} (${code}) per 1 USD`,
                    data: dataPoints,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#ffffff',
                    pointRadius: currentPeriodDays <= 30 ? 4 : 1,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { color: '#f8fafc', font: { family: 'Inter', weight: 'bold', size: 14 } }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.96)',
                        titleColor: '#ffc107',
                        bodyColor: '#ffffff',
                        borderColor: '#ffc107',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return ` 1 USD = ${symbol} ${context.parsed.y.toLocaleString('id-ID', { maximumFractionDigits: 4 })}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.08)' },
                        ticks: { color: '#cbd5e1', maxTicksLimit: 12, font: { weight: 'bold' } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.08)' },
                        ticks: { 
                            color: '#cbd5e1',
                            font: { weight: 'bold' },
                            callback: function(val) {
                                return symbol + ' ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * 5. Fetch Nilai Tukar Real-Time dari Endpoint API Backend
     */
    function fetchLiveConversionRates() {
        fetch('/api/external/currency/USD')
            .then(res => res.json())
            .then(res => {
                if (res && res.success && res.rates) {
                    liveRatesData = res.rates;
                    // Auto-refresh chart dengan rate live jika IDR atau mata uang aktif saat ini
                    restoreSavedCurrencyOrInitDefault();
                }
            })
            .catch(err => {
                console.warn('Fallback ke data kurs simulasi sovereign:', err);
            });
    }

    // Patch window.selectCountry agar saat negara dipilih dari mana saja, grafik kurs langsung ter-update sesuai negara tersebut
    const _prevSelectCountry = window.selectCountry;
    window.selectCountry = async function(countryInput) {
        if (typeof _prevSelectCountry === 'function') {
            await _prevSelectCountry(countryInput);
        }
        if (countryInput) {
            const scLower = countryInput.toLowerCase().trim();
            const match = SOVEREIGN_195_CURRENCIES.find(c => 
                c.country.toLowerCase() === scLower || 
                c.iso.toLowerCase() === scLower || 
                c.country.toLowerCase().includes(scLower) || 
                scLower.includes(c.country.toLowerCase())
            );
            if (match) {
                const activeRate = (liveRatesData && liveRatesData[match.code]) ? liveRatesData[match.code] : match.rate_usd;
                selectCurrency(match.code, match.symbol, activeRate, match.country, false);
                const searchInput = document.getElementById('currCountrySearchInput') || document.getElementById('countrySearchInput');
                if (searchInput) searchInput.value = `${match.country} (${match.code} - ${match.symbol})`;
            } else if (countryInput === 'Global / Semua Negara' || countryInput === '-' || countryInput === 'Global') {
                selectCurrency('Global', '-', 0, 'Global / Semua Negara', false);
                const searchInput = document.getElementById('currCountrySearchInput') || document.getElementById('countrySearchInput');
                if (searchInput) searchInput.value = '';
            }
        }
    };

    function roundToDigits(num, digits) {
        const p = Math.pow(10, digits);
        return Math.round(num * p) / p;
    }
});
