/**
 * Modul Utama Dashboard RiskIntel Hub
 * Mengelola state global, sinkronisasi API asynchronous dengan AbortController (anti-race condition),
 * integrasi pencarian interaktif 195 negara berdaulat, dan sinkronisasi Watchlist favorit dengan database backend.
 */

let currentAbortController = null;
let activeCountryData = {
    name: null,
    iso: null,
    currency: null,
    lat: null,
    lng: null,
    region: 'Global'
};

// Database profil 195 Negara Berdaulat Dunia (UN Sovereign States) beserta koordinat, mata uang, dan region
const countryMetadata = {
    'Afghanistan': { iso: 'AF', currency: 'AFN', lat: 33.9391, lng: 67.7100, region: 'Asia' },
    'Albania': { iso: 'AL', currency: 'ALL', lat: 41.1533, lng: 20.1683, region: 'Europe' },
    'Algeria': { iso: 'DZ', currency: 'DZD', lat: 28.0339, lng: 1.6596, region: 'Africa' },
    'Andorra': { iso: 'AD', currency: 'EUR', lat: 42.5063, lng: 1.5218, region: 'Europe' },
    'Angola': { iso: 'AO', currency: 'AOA', lat: -11.2027, lng: 17.8739, region: 'Africa' },
    'Antigua and Barbuda': { iso: 'AG', currency: 'XCD', lat: 17.0608, lng: -61.7964, region: 'Americas' },
    'Argentina': { iso: 'AR', currency: 'ARS', lat: -38.4161, lng: -63.6167, region: 'Americas' },
    'Armenia': { iso: 'AM', currency: 'AMD', lat: 40.0691, lng: 45.0382, region: 'Asia' },
    'Australia': { iso: 'AU', currency: 'AUD', lat: -25.2744, lng: 133.7751, region: 'Oceania' },
    'Austria': { iso: 'AT', currency: 'EUR', lat: 47.5162, lng: 14.5501, region: 'Europe' },
    'Azerbaijan': { iso: 'AZ', currency: 'AZN', lat: 40.1431, lng: 47.5769, region: 'Asia' },
    'Bahamas': { iso: 'BS', currency: 'BSD', lat: 25.0343, lng: -77.3963, region: 'Americas' },
    'Bahrain': { iso: 'BH', currency: 'BHD', lat: 26.0667, lng: 50.5577, region: 'Asia' },
    'Bangladesh': { iso: 'BD', currency: 'BDT', lat: 23.6850, lng: 90.3563, region: 'Asia' },
    'Barbados': { iso: 'BB', currency: 'BBD', lat: 13.1939, lng: -59.5432, region: 'Americas' },
    'Belarus': { iso: 'BY', currency: 'BYN', lat: 53.7098, lng: 27.9534, region: 'Europe' },
    'Belgium': { iso: 'BE', currency: 'EUR', lat: 50.5039, lng: 4.4699, region: 'Europe' },
    'Belize': { iso: 'BZ', currency: 'BZD', lat: 17.1899, lng: -88.4976, region: 'Americas' },
    'Benin': { iso: 'BJ', currency: 'XOF', lat: 9.3077, lng: 2.3158, region: 'Africa' },
    'Bhutan': { iso: 'BT', currency: 'BTN', lat: 27.5142, lng: 90.4336, region: 'Asia' },
    'Bolivia': { iso: 'BO', currency: 'BOB', lat: -16.2902, lng: -63.5887, region: 'Americas' },
    'Bosnia and Herzegovina': { iso: 'BA', currency: 'BAM', lat: 43.9159, lng: 17.6791, region: 'Europe' },
    'Botswana': { iso: 'BW', currency: 'BWP', lat: -22.3285, lng: 24.6849, region: 'Africa' },
    'Brazil': { iso: 'BR', currency: 'BRL', lat: -14.2350, lng: -51.9253, region: 'Americas' },
    'Brunei': { iso: 'BN', currency: 'BND', lat: 4.5353, lng: 114.7277, region: 'Asia' },
    'Bulgaria': { iso: 'BG', currency: 'BGN', lat: 42.7339, lng: 25.4858, region: 'Europe' },
    'Burkina Faso': { iso: 'BF', currency: 'XOF', lat: 12.2383, lng: -1.5616, region: 'Africa' },
    'Burundi': { iso: 'BI', currency: 'BIF', lat: -3.3731, lng: 29.9189, region: 'Africa' },
    'Cabo Verde': { iso: 'CV', currency: 'CVE', lat: 16.5388, lng: -23.0418, region: 'Africa' },
    'Cambodia': { iso: 'KH', currency: 'KHR', lat: 12.5657, lng: 104.9910, region: 'Asia' },
    'Cameroon': { iso: 'CM', currency: 'XAF', lat: 7.3697, lng: 12.3547, region: 'Africa' },
    'Canada': { iso: 'CA', currency: 'CAD', lat: 56.1304, lng: -106.3468, region: 'Americas' },
    'Central African Republic': { iso: 'CF', currency: 'XAF', lat: 6.6111, lng: 20.9394, region: 'Africa' },
    'Chad': { iso: 'TD', currency: 'XAF', lat: 15.4542, lng: 18.7322, region: 'Africa' },
    'Chile': { iso: 'CL', currency: 'CLP', lat: -35.6751, lng: -71.5430, region: 'Americas' },
    'China': { iso: 'CN', currency: 'CNY', lat: 35.8617, lng: 104.1954, region: 'Asia' },
    'Colombia': { iso: 'CO', currency: 'COP', lat: 4.5709, lng: -74.2973, region: 'Americas' },
    'Comoros': { iso: 'KM', currency: 'KMF', lat: -11.6455, lng: 43.3333, region: 'Africa' },
    'Congo (Brazzaville)': { iso: 'CG', currency: 'XAF', lat: -0.2280, lng: 15.8277, region: 'Africa' },
    'Congo (Kinshasa)': { iso: 'CD', currency: 'CDF', lat: -4.0383, lng: 21.7587, region: 'Africa' },
    'Costa Rica': { iso: 'CR', currency: 'CRC', lat: 9.7489, lng: -83.7534, region: 'Americas' },
    'Croatia': { iso: 'HR', currency: 'EUR', lat: 45.1000, lng: 15.2000, region: 'Europe' },
    'Cuba': { iso: 'CU', currency: 'CUP', lat: 21.5218, lng: -77.7812, region: 'Americas' },
    'Cyprus': { iso: 'CY', currency: 'EUR', lat: 35.1264, lng: 33.4299, region: 'Europe' },
    'Czechia': { iso: 'CZ', currency: 'CZK', lat: 49.8175, lng: 15.4730, region: 'Europe' },
    'Denmark': { iso: 'DK', currency: 'DKK', lat: 56.2639, lng: 9.5018, region: 'Europe' },
    'Djibouti': { iso: 'DJ', currency: 'DJF', lat: 11.8251, lng: 42.5903, region: 'Africa' },
    'Dominica': { iso: 'DM', currency: 'XCD', lat: 15.4150, lng: -61.3710, region: 'Americas' },
    'Dominican Republic': { iso: 'DO', currency: 'DOP', lat: 18.7357, lng: -70.1627, region: 'Americas' },
    'Ecuador': { iso: 'EC', currency: 'USD', lat: -1.8312, lng: -78.1834, region: 'Americas' },
    'Egypt': { iso: 'EG', currency: 'EGP', lat: 26.8206, lng: 30.8025, region: 'Africa' },
    'El Salvador': { iso: 'SV', currency: 'USD', lat: 13.7942, lng: -88.8965, region: 'Americas' },
    'Equatorial Guinea': { iso: 'GQ', currency: 'XAF', lat: 1.6508, lng: 10.2679, region: 'Africa' },
    'Eritrea': { iso: 'ER', currency: 'ERN', lat: 15.1794, lng: 39.7823, region: 'Africa' },
    'Estonia': { iso: 'EE', currency: 'EUR', lat: 58.5953, lng: 25.0136, region: 'Europe' },
    'Eswatini': { iso: 'SZ', currency: 'SZL', lat: -26.5225, lng: 31.4659, region: 'Africa' },
    'Ethiopia': { iso: 'ET', currency: 'ETB', lat: 9.1450, lng: 40.4897, region: 'Africa' },
    'Fiji': { iso: 'FJ', currency: 'FJD', lat: -17.7134, lng: 178.0650, region: 'Oceania' },
    'Finland': { iso: 'FI', currency: 'EUR', lat: 61.9241, lng: 25.7482, region: 'Europe' },
    'France': { iso: 'FR', currency: 'EUR', lat: 46.2276, lng: 2.2137, region: 'Europe' },
    'Gabon': { iso: 'GA', currency: 'XAF', lat: -0.8037, lng: 11.6094, region: 'Africa' },
    'Gambia': { iso: 'GM', currency: 'GMD', lat: 13.4432, lng: -15.3101, region: 'Africa' },
    'Georgia': { iso: 'GE', currency: 'GEL', lat: 42.3154, lng: 43.3569, region: 'Asia' },
    'Germany': { iso: 'DE', currency: 'EUR', lat: 51.1657, lng: 10.4515, region: 'Europe' },
    'Ghana': { iso: 'GH', currency: 'GHS', lat: 7.9465, lng: -1.0232, region: 'Africa' },
    'Greece': { iso: 'GR', currency: 'EUR', lat: 39.0742, lng: 21.8243, region: 'Europe' },
    'Grenada': { iso: 'GD', currency: 'XCD', lat: 12.1165, lng: -61.6790, region: 'Americas' },
    'Guatemala': { iso: 'GT', currency: 'GTQ', lat: 15.7835, lng: -90.2308, region: 'Americas' },
    'Guinea': { iso: 'GN', currency: 'GNF', lat: 9.9456, lng: -9.6966, region: 'Africa' },
    'Guinea-Bissau': { iso: 'GW', currency: 'XOF', lat: 11.8037, lng: -15.1804, region: 'Africa' },
    'Guyana': { iso: 'GY', currency: 'GYD', lat: 4.8604, lng: -58.9302, region: 'Americas' },
    'Haiti': { iso: 'HT', currency: 'HTG', lat: 18.9712, lng: -72.2852, region: 'Americas' },
    'Honduras': { iso: 'HN', currency: 'HNL', lat: 15.2000, lng: -86.2419, region: 'Americas' },
    'Hungary': { iso: 'HU', currency: 'HUF', lat: 47.1625, lng: 19.5033, region: 'Europe' },
    'Iceland': { iso: 'IS', currency: 'ISK', lat: 64.9631, lng: -19.0208, region: 'Europe' },
    'India': { iso: 'IN', currency: 'INR', lat: 20.5937, lng: 78.9629, region: 'Asia' },
    'Indonesia': { iso: 'ID', currency: 'IDR', lat: -0.7893, lng: 113.9213, region: 'Asia', aliases: ['ikn', 'nusantara', 'indo', 'ri'] },
    'Iran': { iso: 'IR', currency: 'IRR', lat: 32.4279, lng: 53.6880, region: 'Asia' },
    'Iraq': { iso: 'IQ', currency: 'IQD', lat: 33.2232, lng: 43.6793, region: 'Asia' },
    'Ireland': { iso: 'IE', currency: 'EUR', lat: 53.4129, lng: -8.2439, region: 'Europe' },
    'Israel': { iso: 'IL', currency: 'ILS', lat: 31.0461, lng: 34.8516, region: 'Asia' },
    'Italy': { iso: 'IT', currency: 'EUR', lat: 41.8719, lng: 12.5674, region: 'Europe' },
    'Ivory Coast': { iso: 'CI', currency: 'XOF', lat: 7.5400, lng: -5.5471, region: 'Africa' },
    'Jamaica': { iso: 'JM', currency: 'JMD', lat: 18.1096, lng: -77.2975, region: 'Americas' },
    'Japan': { iso: 'JP', currency: 'JPY', lat: 36.2048, lng: 138.2529, region: 'Asia', aliases: ['jepang', 'nippon'] },
    'Jordan': { iso: 'JO', currency: 'JOD', lat: 30.5852, lng: 36.2384, region: 'Asia' },
    'Kazakhstan': { iso: 'KZ', currency: 'KZT', lat: 48.0196, lng: 66.9237, region: 'Asia' },
    'Kenya': { iso: 'KE', currency: 'KES', lat: -0.0236, lng: 37.9062, region: 'Africa' },
    'Kiribati': { iso: 'KI', currency: 'AUD', lat: -3.3704, lng: -168.7340, region: 'Oceania' },
    'Kuwait': { iso: 'KW', currency: 'KWD', lat: 29.3117, lng: 47.4818, region: 'Asia' },
    'Kyrgyzstan': { iso: 'KG', currency: 'KGS', lat: 41.2044, lng: 74.7661, region: 'Asia' },
    'Laos': { iso: 'LA', currency: 'LAK', lat: 19.8563, lng: 102.4955, region: 'Asia' },
    'Latvia': { iso: 'LV', currency: 'EUR', lat: 56.8796, lng: 24.6032, region: 'Europe' },
    'Lebanon': { iso: 'LB', currency: 'LBP', lat: 33.8547, lng: 35.8623, region: 'Asia' },
    'Lesotho': { iso: 'LS', currency: 'LSL', lat: -29.6100, lng: 28.2336, region: 'Africa' },
    'Liberia': { iso: 'LR', currency: 'LRD', lat: 6.4281, lng: -9.4295, region: 'Africa' },
    'Libya': { iso: 'LY', currency: 'LYD', lat: 26.3351, lng: 17.2283, region: 'Africa' },
    'Liechtenstein': { iso: 'LI', currency: 'CHF', lat: 47.1660, lng: 9.5554, region: 'Europe' },
    'Lithuania': { iso: 'LT', currency: 'EUR', lat: 55.1694, lng: 23.8813, region: 'Europe' },
    'Luxembourg': { iso: 'LU', currency: 'EUR', lat: 49.8153, lng: 6.1296, region: 'Europe' },
    'Madagascar': { iso: 'MG', currency: 'MGA', lat: -18.7669, lng: 46.8691, region: 'Africa' },
    'Malawi': { iso: 'MW', currency: 'MWK', lat: -13.2543, lng: 34.3015, region: 'Africa' },
    'Malaysia': { iso: 'MY', currency: 'MYR', lat: 4.2105, lng: 101.9758, region: 'Asia' },
    'Maldives': { iso: 'MV', currency: 'MVR', lat: 3.2028, lng: 73.2207, region: 'Asia' },
    'Mali': { iso: 'ML', currency: 'XOF', lat: 17.5707, lng: -3.9962, region: 'Africa' },
    'Malta': { iso: 'MT', currency: 'EUR', lat: 35.9375, lng: 14.3754, region: 'Europe' },
    'Marshall Islands': { iso: 'MH', currency: 'USD', lat: 7.1315, lng: 171.1845, region: 'Oceania' },
    'Mauritania': { iso: 'MR', currency: 'MRU', lat: 21.0079, lng: -10.9408, region: 'Africa' },
    'Mauritius': { iso: 'MU', currency: 'MUR', lat: -20.3484, lng: 57.5522, region: 'Africa' },
    'Mexico': { iso: 'MX', currency: 'MXN', lat: 23.6345, lng: -102.5528, region: 'Americas' },
    'Micronesia': { iso: 'FM', currency: 'USD', lat: 7.4256, lng: 150.5508, region: 'Oceania' },
    'Moldova': { iso: 'MD', currency: 'MDL', lat: 47.4116, lng: 28.3699, region: 'Europe' },
    'Monaco': { iso: 'MC', currency: 'EUR', lat: 43.7384, lng: 7.4246, region: 'Europe' },
    'Mongolia': { iso: 'MN', currency: 'MNT', lat: 46.8625, lng: 103.8467, region: 'Asia' },
    'Montenegro': { iso: 'ME', currency: 'EUR', lat: 42.7087, lng: 19.3744, region: 'Europe' },
    'Morocco': { iso: 'MA', currency: 'MAD', lat: 31.7917, lng: -7.0926, region: 'Africa' },
    'Mozambique': { iso: 'MZ', currency: 'MZN', lat: -18.6657, lng: 35.5296, region: 'Africa' },
    'Myanmar': { iso: 'MM', currency: 'MMK', lat: 21.9162, lng: 95.9560, region: 'Asia' },
    'Namibia': { iso: 'NA', currency: 'NAD', lat: -22.9576, lng: 18.4904, region: 'Africa' },
    'Nauru': { iso: 'NR', currency: 'AUD', lat: -0.5228, lng: 166.9315, region: 'Oceania' },
    'Nepal': { iso: 'NP', currency: 'NPR', lat: 28.3949, lng: 84.1240, region: 'Asia' },
    'Netherlands': { iso: 'NL', currency: 'EUR', lat: 52.1326, lng: 5.2913, region: 'Europe' },
    'New Zealand': { iso: 'NZ', currency: 'NZD', lat: -40.9006, lng: 174.8860, region: 'Oceania' },
    'Nicaragua': { iso: 'NI', currency: 'NIO', lat: 12.8654, lng: -85.2072, region: 'Americas' },
    'Niger': { iso: 'NE', currency: 'XOF', lat: 17.6078, lng: 8.0817, region: 'Africa' },
    'Nigeria': { iso: 'NG', currency: 'NGN', lat: 9.0820, lng: 8.6753, region: 'Africa' },
    'North Korea': { iso: 'KP', currency: 'KPW', lat: 40.3399, lng: 127.5101, region: 'Asia' },
    'North Macedonia': { iso: 'MK', currency: 'MKD', lat: 41.6086, lng: 21.7453, region: 'Europe' },
    'Norway': { iso: 'NO', currency: 'NOK', lat: 60.4720, lng: 8.4689, region: 'Europe' },
    'Oman': { iso: 'OM', currency: 'OMR', lat: 21.5126, lng: 55.9233, region: 'Asia' },
    'Pakistan': { iso: 'PK', currency: 'PKR', lat: 30.3753, lng: 69.3451, region: 'Asia' },
    'Palau': { iso: 'PW', currency: 'USD', lat: 7.5150, lng: 134.5825, region: 'Oceania' },
    'Palestine': { iso: 'PS', currency: 'USD', lat: 31.9522, lng: 35.2332, region: 'Asia' },
    'Panama': { iso: 'PA', currency: 'PAB', lat: 8.5379, lng: -80.7821, region: 'Americas' },
    'Papua New Guinea': { iso: 'PG', currency: 'PGK', lat: -6.3149, lng: 143.9555, region: 'Oceania' },
    'Paraguay': { iso: 'PY', currency: 'PYG', lat: -23.4425, lng: -58.4438, region: 'Americas' },
    'Peru': { iso: 'PE', currency: 'PEN', lat: -9.1900, lng: -75.0152, region: 'Americas' },
    'Philippines': { iso: 'PH', currency: 'PHP', lat: 12.8797, lng: 121.7740, region: 'Asia' },
    'Poland': { iso: 'PL', currency: 'PLN', lat: 51.9194, lng: 19.1451, region: 'Europe' },
    'Portugal': { iso: 'PT', currency: 'EUR', lat: 39.3999, lng: -8.2245, region: 'Europe' },
    'Qatar': { iso: 'QA', currency: 'QAR', lat: 25.3548, lng: 51.1839, region: 'Asia' },
    'Romania': { iso: 'RO', currency: 'RON', lat: 45.9432, lng: 24.9668, region: 'Europe' },
    'Russia': { iso: 'RU', currency: 'RUB', lat: 61.5240, lng: 105.3188, region: 'Europe/Asia' },
    'Rwanda': { iso: 'RW', currency: 'RWF', lat: -1.9403, lng: 29.8739, region: 'Africa' },
    'Saint Kitts and Nevis': { iso: 'KN', currency: 'XCD', lat: 17.3578, lng: -62.7830, region: 'Americas' },
    'Saint Lucia': { iso: 'LC', currency: 'XCD', lat: 13.9094, lng: -60.9789, region: 'Americas' },
    'Saint Vincent and the Grenadines': { iso: 'VC', currency: 'XCD', lat: 12.9843, lng: -61.2872, region: 'Americas' },
    'Samoa': { iso: 'WS', currency: 'WST', lat: -13.7590, lng: -172.1046, region: 'Oceania' },
    'San Marino': { iso: 'SM', currency: 'EUR', lat: 43.9424, lng: 12.4578, region: 'Europe' },
    'Sao Tome and Principe': { iso: 'ST', currency: 'STN', lat: 0.1864, lng: 6.6131, region: 'Africa' },
    'Saudi Arabia': { iso: 'SA', currency: 'SAR', lat: 23.8859, lng: 45.0792, region: 'Asia' },
    'Senegal': { iso: 'SN', currency: 'XOF', lat: 14.4974, lng: -14.4524, region: 'Africa' },
    'Serbia': { iso: 'RS', currency: 'RSD', lat: 44.0165, lng: 21.0059, region: 'Europe' },
    'Seychelles': { iso: 'SC', currency: 'SCR', lat: -4.6796, lng: 55.4920, region: 'Africa' },
    'Sierra Leone': { iso: 'SL', currency: 'SLL', lat: 8.4606, lng: -11.7799, region: 'Africa' },
    'Singapore': { iso: 'SG', currency: 'SGD', lat: 1.3521, lng: 103.8198, region: 'Asia' },
    'Slovakia': { iso: 'SK', currency: 'EUR', lat: 48.6690, lng: 19.6990, region: 'Europe' },
    'Slovenia': { iso: 'SI', currency: 'EUR', lat: 46.1512, lng: 14.9955, region: 'Europe' },
    'Solomon Islands': { iso: 'SB', currency: 'SBD', lat: -9.6457, lng: 160.1562, region: 'Oceania' },
    'Somalia': { iso: 'SO', currency: 'SOS', lat: 5.1521, lng: 46.1996, region: 'Africa' },
    'South Africa': { iso: 'ZA', currency: 'ZAR', lat: -30.5595, lng: 22.9375, region: 'Africa' },
    'South Korea': { iso: 'KR', currency: 'KRW', lat: 35.9078, lng: 127.7669, region: 'Asia' },
    'South Sudan': { iso: 'SS', currency: 'SSP', lat: 6.8770, lng: 31.3070, region: 'Africa' },
    'Spain': { iso: 'ES', currency: 'EUR', lat: 40.4637, lng: -3.7492, region: 'Europe' },
    'Sri Lanka': { iso: 'LK', currency: 'LKR', lat: 7.8731, lng: 80.7718, region: 'Asia' },
    'Sudan': { iso: 'SD', currency: 'SDG', lat: 12.8628, lng: 30.2176, region: 'Africa' },
    'Suriname': { iso: 'SR', currency: 'SRD', lat: 3.9193, lng: -56.0278, region: 'Americas' },
    'Sweden': { iso: 'SE', currency: 'SEK', lat: 60.1282, lng: 18.6435, region: 'Europe' },
    'Switzerland': { iso: 'CH', currency: 'CHF', lat: 46.8182, lng: 8.2275, region: 'Europe' },
    'Syria': { iso: 'SY', currency: 'SYP', lat: 34.8021, lng: 38.9968, region: 'Asia' },
    'Tajikistan': { iso: 'TJ', currency: 'TJS', lat: 38.8610, lng: 71.2761, region: 'Asia' },
    'Tanzania': { iso: 'TZ', currency: 'TZS', lat: -6.3690, lng: 34.8888, region: 'Africa' },
    'Thailand': { iso: 'TH', currency: 'THB', lat: 15.8700, lng: 100.9925, region: 'Asia' },
    'Timor-Leste': { iso: 'TL', currency: 'USD', lat: -8.8742, lng: 125.7275, region: 'Asia' },
    'Togo': { iso: 'TG', currency: 'XOF', lat: 8.6195, lng: 0.8248, region: 'Africa' },
    'Tonga': { iso: 'TO', currency: 'TOP', lat: -21.1789, lng: -175.1982, region: 'Oceania' },
    'Trinidad and Tobago': { iso: 'TT', currency: 'TTD', lat: 10.6918, lng: -61.2225, region: 'Americas' },
    'Tunisia': { iso: 'TN', currency: 'TND', lat: 33.8869, lng: 9.5375, region: 'Africa' },
    'Turkey': { iso: 'TR', currency: 'TRY', lat: 38.9637, lng: 35.2433, region: 'Asia/Europe' },
    'Turkmenistan': { iso: 'TM', currency: 'TMT', lat: 38.9697, lng: 59.5563, region: 'Asia' },
    'Tuvalu': { iso: 'TV', currency: 'AUD', lat: -7.1095, lng: 177.6493, region: 'Oceania' },
    'Uganda': { iso: 'UG', currency: 'UGX', lat: 1.3733, lng: 32.2903, region: 'Africa' },
    'Ukraine': { iso: 'UA', currency: 'UAH', lat: 48.3794, lng: 31.1656, region: 'Europe' },
    'United Arab Emirates': { iso: 'AE', currency: 'AED', lat: 23.4241, lng: 53.8478, region: 'Asia' },
    'United Kingdom': { iso: 'GB', currency: 'GBP', lat: 55.3781, lng: -3.4360, region: 'Europe', aliases: ['uk', 'england', 'britain', 'inggris'] },
    'United States': { iso: 'US', currency: 'USD', lat: 37.0902, lng: -95.7129, region: 'Americas', aliases: ['usa', 'america', 'as', 'amerika'] },
    'Uruguay': { iso: 'UY', currency: 'UYU', lat: -32.5228, lng: -55.7658, region: 'Americas' },
    'Uzbekistan': { iso: 'UZ', currency: 'UZS', lat: 41.3775, lng: 64.5853, region: 'Asia' },
    'Vanuatu': { iso: 'VU', currency: 'VUV', lat: -15.3767, lng: 166.9592, region: 'Oceania' },
    'Vatican City': { iso: 'VA', currency: 'EUR', lat: 41.9029, lng: 12.4534, region: 'Europe' },
    'Venezuela': { iso: 'VE', currency: 'VES', lat: 6.4238, lng: -66.5897, region: 'Americas' },
    'Vietnam': { iso: 'VN', currency: 'VND', lat: 14.0583, lng: 108.2772, region: 'Asia' },
    'Yemen': { iso: 'YE', currency: 'YER', lat: 15.5527, lng: 48.5164, region: 'Asia' },
    'Zambia': { iso: 'ZM', currency: 'ZMW', lat: -13.1339, lng: 27.8493, region: 'Africa' },
    'Zimbabwe': { iso: 'ZW', currency: 'ZWL', lat: -19.0154, lng: 29.1549, region: 'Africa' }
};

document.addEventListener('DOMContentLoaded', async () => {
    // 1. Inisialisasi Input Pencarian Interaktif untuk 195 Negara Berdaulat (FAIL-SAFE UTAMA)
    const searchInput = document.getElementById('countrySearchInput');
    const dropdownList = document.getElementById('countryDropdownList');

    if (searchInput && dropdownList) {
        const countryKeys = Object.keys(countryMetadata);

        const renderCountryOptions = (query = '') => {
            dropdownList.innerHTML = '';
            const cleanQuery = query.trim().toLowerCase();

            const filtered = countryKeys.filter(key => {
                if (!cleanQuery) return true;
                const meta = countryMetadata[key];
                if (key.toLowerCase().includes(cleanQuery)) return true;
                if (meta.iso.toLowerCase().includes(cleanQuery)) return true;
                if (meta.currency.toLowerCase().includes(cleanQuery)) return true;
                if (meta.aliases && meta.aliases.some(alias => alias.toLowerCase().includes(cleanQuery))) return true;
                return false;
            });

            if (filtered.length === 0) {
                dropdownList.innerHTML = `<div class="p-3 text-muted small text-center">Negara "${escapeHtml(query)}" tidak ditemukan. Coba nama/kode ISO lain.</div>`;
            } else {
                filtered.slice(0, 100).forEach(key => {
                    const meta = countryMetadata[key];
                    const item = document.createElement('div');
                    item.className = 'dropdown-item d-flex justify-content-between align-items-center py-2 px-3 text-white border-bottom border-secondary';
                    item.style.cursor = 'pointer';
                    item.style.fontSize = '13px';
                    item.innerHTML = `
                        <div>
                            <span class="fw-bold me-1">${escapeHtml(key)}</span>
                            <span class="badge bg-dark border border-secondary text-warning small">${escapeHtml(meta.iso)}</span>
                        </div>
                        <span class="small text-secondary">${escapeHtml(meta.currency)} (${escapeHtml(meta.region)})</span>
                    `;
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        searchInput.value = `${key} (${meta.iso} - ${meta.currency})`;
                        dropdownList.style.display = 'none';
                        selectCountry(key);
                    });
                    item.addEventListener('mouseenter', () => item.style.background = 'rgba(200, 156, 98, 0.2)');
                    item.addEventListener('mouseleave', () => item.style.background = 'transparent');
                    dropdownList.appendChild(item);
                });
            }
            dropdownList.style.display = 'block';
        };

        window.openCountryDropdown = function(query = '') {
            renderCountryOptions(query);
            if (dropdownList) dropdownList.scrollTop = 0;
        };

        searchInput.addEventListener('input', (e) => {
            renderCountryOptions(e.target.value);
        });

        searchInput.addEventListener('focus', () => {
            renderCountryOptions(searchInput.value.replace(/\s*\(.*\)/, ''));
        });

        searchInput.addEventListener('click', (e) => {
            e.stopPropagation();
            renderCountryOptions(searchInput.value.replace(/\s*\(.*\)/, ''));
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !dropdownList.contains(e.target)) {
                dropdownList.style.display = 'none';
            }
        });
    }

    // 2. Inisialisasi Peta Maritim (Dalam Try-Catch agar tidak memblokir JS jika CDN Leaflet bermasalah)
    try {
        if (typeof initMaritimeMap === 'function') {
            initMaritimeMap();
        }
    } catch (err) {
        console.warn("Maritime map init bypassed gracefully:", err);
    }

    // 3. Tombol muat ulang data real-time
    const refreshBtn = document.getElementById('refreshAllBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            if (activeCountryData.name) {
                selectCountry(activeCountryData.name);
            }
        });
    }

    // 4. Inisialisasi Form Analisis Sentimen AI
    const sentimentForm = document.getElementById('sentimentForm');
    if (sentimentForm) {
        sentimentForm.addEventListener('submit', handleSentimentAnalysis);
    }

    // 5. Inisialisasi Simulator Prediksi Risiko AI
    const riskSimulatorForm = document.getElementById('riskSimulatorForm');
    if (riskSimulatorForm) {
        riskSimulatorForm.addEventListener('submit', handleRiskSimulation);
    }

    // 6. Muat daftar pantauan (Watchlist) dari database server
    await loadWatchlistsFromServer();

window.getFeatureStorageKey = function() {
    const path = window.location.pathname || '';
    if (path.includes('/news')) return 'selected_country_news';
    if (path.includes('/ports')) return 'selected_country_ports';
    if (path.includes('/risk-simulator')) return 'selected_country_risk';
    if (path.includes('/watchlist')) return 'selected_country_watchlist';
    if (path.includes('/analytics/currency') || path.includes('/currency')) return 'selected_country_currency';
    if (path.includes('/analytics')) return 'selected_country_analytics';
    if (path.includes('/decision-support')) return 'selected_country_decision_support';
    return 'selected_country_dashboard';
};

    // 7. Always start unselected on every page load — user must pick a country manually
    const featKey = window.getFeatureStorageKey();
    // Clear any stored country so refresh always starts blank
    sessionStorage.removeItem(featKey);
    localStorage.removeItem(featKey);
    sessionStorage.removeItem(featKey + '_curr');
    localStorage.removeItem(featKey + '_curr');
    // Start in unselected state (shows '-')
    selectCountry('Global / Semua Negara');

    // Jika saat ini di halaman Dashboard utama, pasang sinkronisasi negara ke link sidebar
    if (window.location.pathname === '/' || window.location.pathname === '/dashboard') {
        const sidebarLinks = document.querySelectorAll('#sidebarMain a.sidebar-link');
        sidebarLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (href.includes('/ports') || href.includes('/news-sentiment') || href.includes('/watchlist') || href.includes('/analytics'))) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.navigateToFeatureFromDashboard(e, href);
                });
            }
        });
    }
});

window.navigateToFeatureFromDashboard = function(event, targetUrl) {
    if (event) event.preventDefault();
    // Always navigate without carrying country — target page starts blank
    window.location.href = targetUrl;
    return false;
};

window.resetToAdminFeed = function() {
    const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_dashboard';
    sessionStorage.removeItem(featKey);
    localStorage.removeItem(featKey);
    sessionStorage.removeItem(featKey + '_curr');
    localStorage.removeItem(featKey + '_curr');
    const searchInputEl = document.getElementById('countrySearchInput');
    if (searchInputEl) {
        searchInputEl.value = '';
    }
    selectCountry('Global / Semua Negara');
    setTimeout(() => {
        if (searchInputEl && typeof window.openCountryDropdown === 'function') {
            searchInputEl.focus();
            window.openCountryDropdown('');
        }
    }, 150);
};

/**
 * Memilih negara aktif dan memperbarui semua indikator real-time dengan AbortController
 */
async function selectCountry(countryInput) {
    const isUnselectedInput = (countryInput === 'Global / Semua Negara' || countryInput === 'Global / Semua Negara (Feed Artikel Admin)' || countryInput === 'Global' || countryInput === 'Belum Dipilih' || countryInput === '-' || !countryInput);
    let countryName = countryInput;
    if (!isUnselectedInput && !countryMetadata[countryName]) {
        const qLower = (countryInput || '').toString().trim().toLowerCase();
        for (const key of Object.keys(countryMetadata)) {
            if (key.toLowerCase() === qLower || countryMetadata[key].iso.toLowerCase() === qLower || countryMetadata[key].currency.toLowerCase() === qLower) {
                countryName = key;
                break;
            }
        }
        if (!countryMetadata[countryName]) {
            for (const key of Object.keys(countryMetadata)) {
                if (key.toLowerCase().includes(qLower) || qLower.includes(key.toLowerCase())) {
                    countryName = key;
                    break;
                }
            }
        }
    }
    if (!isUnselectedInput && !countryMetadata[countryName]) return;

    // Simpan pilihan secara independen per fitur ke sessionStorage & localStorage
    const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_dashboard';
    const isUnselected = isUnselectedInput || (countryName === 'Global / Semua Negara' || countryName === 'Global / Semua Negara (Feed Artikel Admin)' || countryName === 'Global' || countryName === 'Belum Dipilih' || countryName === '-' || !countryName);

    if (isUnselected) {
        sessionStorage.removeItem(featKey);
        localStorage.removeItem(featKey);
        countryName = 'Global / Semua Negara';
    } else {
        // Do NOT persist country to storage — every refresh starts blank
        sessionStorage.removeItem(featKey);
        localStorage.removeItem(featKey);
    }

    const searchInputEl = document.getElementById('countrySearchInput');
    if (searchInputEl) {
        if (isUnselected) {
            searchInputEl.value = '';
        } else {
            const meta = countryMetadata[countryName];
            searchInputEl.value = `${countryName} (${meta.iso} - ${meta.currency})`;
        }
    }

    // Batalkan request yang sedang berjalan agar tidak terjadi race condition
    if (currentAbortController) {
        currentAbortController.abort();
    }
    currentAbortController = new AbortController();
    const signal = currentAbortController.signal;

    activeCountryData = isUnselected ? { name: 'Global / Semua Negara', iso: '-', currency: 'USD', lat: 0.0000, lng: 0.0000, region: 'Global' } : { name: countryName, ...countryMetadata[countryName] };

    // Update UI Header Negara
    const nameEl = document.getElementById('selectedCountryName');
    const regionEl = document.getElementById('selectedCountryRegion');
    const currEl = document.getElementById('selectedCountryCurrency');
    if (isUnselected) {
        if (nameEl) nameEl.textContent = '-';
        if (regionEl) regionEl.textContent = '-';
        if (currEl) currEl.textContent = '-';
    } else {
        if (nameEl) nameEl.textContent = escapeHtml(activeCountryData.name);
        if (regionEl) regionEl.textContent = escapeHtml(activeCountryData.region);
        if (currEl) currEl.textContent = escapeHtml(activeCountryData.currency);
    }

    // Sinkronisasi dengan filterCountry (Data Visualization Dashboard / analytics/index.blade.php)
    const filterCountryEl = document.getElementById('filterCountry');
    if (filterCountryEl) {
        filterCountryEl.value = isUnselected ? '' : activeCountryData.name;
        if (typeof window.fetchFilteredAnalytics === 'function') {
            window.fetchFilteredAnalytics();
        } else if (typeof fetchFilteredAnalytics === 'function') {
            fetchFilteredAnalytics();
        }
    }

    // Sinkronisasi dengan Currency Impact Dashboard jika aktif
    const currNameEl = document.getElementById('currSelectedCountryName');
    if (currNameEl && typeof selectCurrency === 'function' && activeCountryData.currency) {
        if (typeof currentSelectedCountry === 'undefined' || currentSelectedCountry !== activeCountryData.name) {
            if (isUnselected) {
                selectCurrency('USD', '$', 1, '-', false);
            } else {
                selectCurrency(activeCountryData.currency, '---', 0, activeCountryData.name, false);
            }
        }
    }

    // Sinkronisasi dengan Decision Support Center jika aktif
    if (typeof window.triggerAjaxSimulation === 'function') {
        window.triggerAjaxSimulation();
    }

    if (isUnselected) {
        // Reset kartu Cuaca Maritim ke '-'
        const tempEl = document.getElementById('valWeatherTemp');
        const windEl = document.getElementById('valWeatherWind');
        const windDirEl = document.getElementById('valWeatherWindDir');
        const rainEl = document.getElementById('valWeatherRain');
        const humidityEl = document.getElementById('valWeatherHumidity');
        const cloudEl = document.getElementById('valWeatherCloud');
        const pressureEl = document.getElementById('valWeatherPressure');
        if (tempEl) tempEl.textContent = '-';
        if (windEl) windEl.textContent = '-';
        if (windDirEl) windDirEl.textContent = '-';
        if (rainEl) rainEl.textContent = '-';
        if (humidityEl) humidityEl.textContent = '-';
        if (cloudEl) cloudEl.textContent = '-';
        if (pressureEl) pressureEl.textContent = '-';

        // Reset kartu Metrik Ekonomi ke '-'
        const gdpEl = document.getElementById('valEconGdp');
        const infEl = document.getElementById('valEconInf');
        const popEl = document.getElementById('valEconPop');
        if (gdpEl) gdpEl.textContent = '-';
        if (infEl) infEl.textContent = '-';
        if (popEl) popEl.textContent = '-';

        // Reset kartu Kurs Valuta ke '-'
        const rateEl = document.getElementById('valCurrencyRate');
        const baseEl = document.getElementById('valCurrencyBase');
        if (rateEl) rateEl.textContent = '-';
        if (baseEl) baseEl.textContent = '-';

        // Reset kartu Skor Risiko ke '-'
        const riskScoreEl = document.getElementById('valTotalRiskScore');
        const riskStatusEl = document.getElementById('valRiskStatusBadge');
        if (riskScoreEl) riskScoreEl.textContent = '-';
        if (riskStatusEl) {
            riskStatusEl.textContent = '-';
            riskStatusEl.className = 'badge bg-secondary px-3 py-2 fw-bold text-white';
        }

        unsetCardLoading('weatherCard');
        unsetCardLoading('economyCard');
        unsetCardLoading('currencyCard');
        setCardLoading('newsCard');
        try {
            await Promise.all([
                fetchNews('Global / Semua Negara', signal),
                typeof loadPortsForCountry === 'function' ? loadPortsForCountry('Global / Semua Negara', signal, 0, 0) : Promise.resolve()
            ]);
        } catch (e) {
            if (e.name !== 'AbortError') console.error(e);
        }
        return;
    }

    // Tampilkan animasi loading pada kartu
    setCardLoading('weatherCard');
    setCardLoading('economyCard');
    setCardLoading('currencyCard');
    setCardLoading('newsCard');

    try {
        // Eksekusi API secara paralel yang dilindungi signal
        await Promise.all([
            fetchWeather(activeCountryData.lat, activeCountryData.lng, signal),
            fetchEconomy(activeCountryData.iso, signal),
            fetchCurrency(activeCountryData.currency, signal),
            fetchNews(activeCountryData.name, signal),
            typeof loadPortsForCountry === 'function' ? loadPortsForCountry(activeCountryData.name, signal, activeCountryData.lat, activeCountryData.lng) : Promise.resolve(),
            updateRealtimeRiskScore(activeCountryData.name, activeCountryData.iso, signal)
        ]);
    } catch (e) {
        if (e.name !== 'AbortError') {
            console.error("Error dalam siklus perbaruan negara:", e);
        }
    }
}

function setCardLoading(cardId) {
    const el = document.getElementById(cardId);
    if (el) {
        el.style.opacity = '0.5';
        el.style.transition = 'opacity 0.3s';
    }
}

function unsetCardLoading(cardId) {
    const el = document.getElementById(cardId);
    if (el) {
        el.style.opacity = '1';
    }
}

/**
 * Konversi derajat arah angin (0-360) menjadi arah mata angin kompas
 */
function getCardDirection(deg) {
    if (deg === null || deg === undefined || isNaN(deg)) return 'N/A';
    const dirs = ['Utara', 'Timur Laut', 'Timur', 'Tenggara', 'Selatan', 'Barat Daya', 'Barat', 'Barat Laut'];
    const idx = Math.round(((deg %= 360) < 0 ? deg + 360 : deg) / 45) % 8;
    return `${dirs[idx]} (${Math.round(deg)}°)`;
}

/**
 * Mengambil data cuaca maritim dari Open-Meteo Proxy
 */
async function fetchWeather(lat, lng, signal) {
    try {
        const res = await fetch(`/api/external/weather/${lat}/${lng}`, { signal });
        const result = await res.json();
        unsetCardLoading('weatherCard');

        if (result.success && result.data) {
            const tempEl = document.getElementById('valWeatherTemp');
            const windEl = document.getElementById('valWeatherWind');
            const windDirEl = document.getElementById('valWeatherWindDir');
            const rainEl = document.getElementById('valWeatherRain');
            const humidityEl = document.getElementById('valWeatherHumidity');
            const cloudEl = document.getElementById('valWeatherCloud');
            const pressureEl = document.getElementById('valWeatherPressure');

            if (tempEl) tempEl.textContent = `${result.data.temperature_2m ?? '--'} °C`;
            if (windEl) windEl.textContent = `${result.data.wind_speed_10m ?? '--'} m/s`;
            if (windDirEl) windDirEl.textContent = getCardDirection(result.data.wind_direction);
            if (rainEl) rainEl.textContent = `${result.data.rain ?? result.data.precipitation ?? 0} mm/h`;
            if (humidityEl) humidityEl.textContent = `${result.data.humidity ?? 78} %`;
            if (cloudEl) cloudEl.textContent = `${result.data.cloud_cover ?? 45} %`;
            if (pressureEl) pressureEl.textContent = `${result.data.surface_pressure ?? 1013} hPa`;
        }
    } catch (e) {
        if (e.name !== 'AbortError') unsetCardLoading('weatherCard');
    }
}

/**
 * Mengambil indikator ekonomi dari World Bank Proxy
 */
async function fetchEconomy(isoCode, signal) {
    try {
        const res = await fetch(`/api/external/economy/${isoCode}`, { signal });
        const result = await res.json();
        unsetCardLoading('economyCard');

        if (result.success && result.data) {
            const gdpEl = document.getElementById('valEconGdp');
            const infEl = document.getElementById('valEconInf');
            const popEl = document.getElementById('valEconPop');

            if (gdpEl) {
                const gdpVal = result.data.gdp;
                gdpEl.textContent = gdpVal ? (gdpVal >= 1e12 ? `$${(gdpVal / 1e12).toFixed(2)} Trillion` : `$${(gdpVal / 1e9).toFixed(2)} Billion`) : 'N/A';
            }
            if (infEl) {
                const infVal = result.data.inflation;
                infEl.textContent = infVal ? `${infVal.toFixed(1)} %` : 'N/A';
            }
            if (popEl) {
                const popVal = result.data.population;
                popEl.textContent = popVal ? `${(popVal / 1e6).toFixed(1)} M` : 'N/A';
            }
        }
    } catch (e) {
        if (e.name !== 'AbortError') unsetCardLoading('economyCard');
    }
}

/**
 * Mengambil kurs pertukaran dari ExchangeRate Proxy
 */
async function fetchCurrency(targetCurrency, signal) {
    try {
        const res = await fetch(`/api/external/currency/USD`, { signal });
        const result = await res.json();
        unsetCardLoading('currencyCard');

        if (result.success && result.rates) {
            const rate = result.rates[targetCurrency] || 1;
            const rateEl = document.getElementById('valCurrencyRate');
            const baseEl = document.getElementById('valCurrencyBase');
            if (rateEl) rateEl.textContent = `1 USD = ${rate.toLocaleString('en-US', { maximumFractionDigits: 2 })} ${escapeHtml(targetCurrency)}`;
            if (baseEl) baseEl.textContent = `Base: USD -> Target: ${escapeHtml(targetCurrency)}`;
        }
    } catch (e) {
        if (e.name !== 'AbortError') unsetCardLoading('currencyCard');
    }
}

/**
 * Mengambil berita rantai pasok global dan merender dengan perlindungan escapeHtml()
 */
async function fetchNews(countryName, signal) {
    const newsContainer = document.getElementById('newsListContainer');
    if (!newsContainer) return;

    const renderCardHtml = (art, isAdminCard) => {
        const safeTitle = escapeHtml(art.title || 'Untitled Article');
        const safeDesc = escapeHtml(art.description || 'No detailed preview available.');
        const safeUrl = escapeHtml(art.url || '#');
        const safeAuthor = escapeHtml(art.author || 'RiskIntel Analyst');
        const safeSource = escapeHtml(art.source?.name || (isAdminCard ? 'Internal Analysis' : 'GNews API'));
        const sentiment = escapeHtml(art.sentiment || 'Neutral');
        const safeDate = escapeHtml(art.created_at || 'Live GNews');

        let badgeColor = 'badge-soft-secondary';
        if (sentiment === 'Positive') badgeColor = 'badge-soft-success';
        if (sentiment === 'Negative') badgeColor = 'badge-soft-danger';

        const authorBadge = isAdminCard 
            ? `<div class="small text-muted mb-2" style="font-size: 11.5px;"><i class="fa-solid fa-user-pen me-1 text-primary"></i> Author: <strong class="text-dark">${safeAuthor}</strong></div>` 
            : '';

        const footerInfo = isAdminCard 
            ? `<span class="small text-muted" style="font-size: 11px;"><i class="fa-solid fa-file-lines me-1 text-primary"></i> Internal Report</span>`
            : `<span class="small text-muted" style="font-size: 11px;"><i class="fa-solid fa-clock me-1 text-primary"></i> Live GNews</span>`;

        let actionBtn = '';
        if (isAdminCard) {
            if (art.url && art.url !== '#' && art.url !== '') {
                actionBtn = `<a href="${safeUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary fw-medium py-1 px-2.5"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Visit Source</a>`;
            } else {
                actionBtn = `<span class="btn btn-sm btn-outline-secondary fw-medium py-1 px-2.5 disabled"><i class="fa-solid fa-shield-halved me-1 text-primary"></i> Internal</span>`;
            }
        } else {
            actionBtn = `<a href="${safeUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary fw-medium py-1 px-2.5">Read More <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a>`;
        }

        return `
            <div class="col-12 col-md-4 d-flex mb-3">
                <div class="news-item p-3.5 rounded-3 glass-card w-100 d-flex flex-column justify-content-between" style="border-top: 3.5px solid ${isAdminCard ? 'var(--primary)' : '#0ea5e9'}; word-break: break-word; overflow-wrap: anywhere;">
                    <div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                            <span class="badge ${badgeColor} px-2.5 py-1 fw-semibold">${sentiment}</span>
                            <span class="small fw-semibold text-muted text-end" style="font-size: 11.5px; word-break: break-word;"><i class="fa-solid fa-satellite-dish me-1 text-primary"></i> ${safeSource}</span>
                        </div>
                        ${authorBadge}
                        <h5 class="mb-2 fw-semibold text-dark" style="font-size: 15px; line-height: 1.4; word-break: break-word; overflow-wrap: anywhere;">
                            <a href="${safeUrl !== '#' ? safeUrl : 'javascript:void(0)'}" ${safeUrl !== '#' ? 'target="_blank" rel="noopener noreferrer"' : ''} class="text-dark text-decoration-none" style="transition: color 0.2s;">${safeTitle}</a>
                        </h5>
                        <p class="small mb-3 text-muted" style="font-size: 12.5px; line-height: 1.55; word-break: break-word; overflow-wrap: anywhere; white-space: normal;">${safeDesc}</p>
                    </div>
                    <div class="mt-auto pt-2.5 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
                        ${footerInfo}
                        ${actionBtn}
                    </div>
                </div>
            </div>
        `;
    };

    try {
        const res = await fetch(`/api/external/news/${encodeURIComponent(countryName)}`, { signal });
        const result = await res.json();
        unsetCardLoading('newsCard');

        if (result.success && ((result.articles && result.articles.length > 0) || (result.admin_articles && result.admin_articles.length > 0))) {
            const badgeEl = document.getElementById('newsCountBadge');
            if (badgeEl) {
                if (result.is_admin_feed) {
                    badgeEl.textContent = `Feed Artikel Admin (${(result.admin_articles || result.articles).length} Artikel)`;
                    badgeEl.className = 'badge bg-warning text-dark border border-warning fw-bold small px-3 py-2';
                } else {
                    badgeEl.textContent = `Artikel Admin & Live News (${countryName})`;
                    badgeEl.className = 'badge bg-info text-dark border border-info fw-bold small px-3 py-2';
                }
            }

            if (result.is_admin_feed) {
                const adminList = result.admin_articles || result.articles || [];
                newsContainer.innerHTML = adminList.map(art => renderCardHtml(art, true)).join('');
            } else {
                const adminList = result.admin_articles || [];
                const liveList = (result.articles || []).slice(0, 3);

                let html = '';
                if (adminList.length > 0) {
                    html += `
                        <div class="col-12 mb-1">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: rgba(245, 158, 11, 0.15); border-left: 4px solid #f59e0b;">
                                <div class="fw-bold text-warning mb-0"><i class="fa-solid fa-newspaper me-2"></i>Artikel Analisis Internal Admin (Tetap Ditampilkan untuk Semua Negara)</div>
                                <span class="badge bg-warning text-dark fw-bold">${adminList.length} Artikel Admin</span>
                            </div>
                        </div>
                    `;
                    html += adminList.map(art => renderCardHtml(art, true)).join('');
                }

                if (liveList.length > 0) {
                    html += `
                        <div class="col-12 mt-4 mb-1">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: rgba(56, 189, 248, 0.15); border-left: 4px solid #38bdf8;">
                                <div class="fw-bold text-info mb-0"><i class="fa-solid fa-globe me-2"></i>Berita Logistik Terkini GNews - Negara Tujuan: ${escapeHtml(countryName)}</div>
                                <span class="badge bg-info text-dark fw-bold">${liveList.length} Berita Live</span>
                            </div>
                        </div>
                    `;
                    html += liveList.map(art => renderCardHtml(art, false)).join('');
                }

                newsContainer.innerHTML = html;
            }
        } else {
            // Jika result kosong / gagal dari live news, tampilkan artikel Admin Dashboard
            const fallbackRes = await fetch('/api/external/news/Global');
            const fallbackData = await fallbackRes.json();
            if (fallbackData && fallbackData.admin_articles && fallbackData.admin_articles.length > 0) {
                const badgeEl = document.getElementById('newsCountBadge');
                if (badgeEl) {
                    badgeEl.textContent = `Feed Artikel Admin (${fallbackData.admin_articles.length} Artikel)`;
                    badgeEl.className = 'badge bg-warning text-dark border border-warning fw-bold small px-3 py-2';
                }
                newsContainer.innerHTML = fallbackData.admin_articles.map(art => renderCardHtml(art, true)).join('');
            } else {
                newsContainer.innerHTML = `<div class="col-12 small p-5 text-center" style="color: #cbd5e1;">Belum ada artikel di Admin Dashboard.</div>`;
            }
        }
    } catch (e) {
        if (e.name !== 'AbortError') {
            unsetCardLoading('newsCard');
            if (newsContainer) {
                try {
                    const fallbackRes = await fetch('/api/external/news/Global');
                    const fallbackData = await fallbackRes.json();
                    if (fallbackData && fallbackData.admin_articles && fallbackData.admin_articles.length > 0) {
                        const badgeEl = document.getElementById('newsCountBadge');
                        if (badgeEl) {
                            badgeEl.textContent = `Feed Artikel Admin (${fallbackData.admin_articles.length} Artikel)`;
                            badgeEl.className = 'badge bg-warning text-dark border border-warning fw-bold small px-3 py-2';
                        }
                        newsContainer.innerHTML = fallbackData.admin_articles.map(art => renderCardHtml(art, true)).join('');
                        return;
                    }
                } catch (err) {}
                newsContainer.innerHTML = `<div class="text-danger small p-3 text-center">Gagal memuat berita terkini.</div>`;
            }
        }
    }
}

/**
 * Mengupdate skor risiko real-time dari backend service
 */
async function updateRealtimeRiskScore(countryName, isoCode, signal) {
    try {
        const res = await fetch(`/api/ai/predict-risk?country=${encodeURIComponent(countryName)}&iso=${encodeURIComponent(isoCode)}&wind=12&inflation=3.5&exchange=150`, { signal });
        const result = await res.json();

        if (result.success && result.prediction) {
            const scoreEl = document.getElementById('valTotalRiskScore');
            const statusEl = document.getElementById('valRiskStatusBadge');
            if (scoreEl) scoreEl.textContent = result.prediction.total_risk_score;
            if (statusEl) {
                statusEl.textContent = escapeHtml(result.prediction.risk_status);
                statusEl.className = 'badge px-3 py-2 ' + (
                    result.prediction.risk_status.includes('HIGH') ? 'bg-danger' :
                    result.prediction.risk_status.includes('MEDIUM') ? 'bg-warning text-dark' : 'bg-success'
                );
            }
        }
    } catch (e) {
        if (e.name !== 'AbortError') console.error("Risk Score Update Error:", e);
    }
}

/**
 * Handle Analisis Sentimen Teks (AI Lexicon Service)
 */
async function handleSentimentAnalysis(e) {
    e.preventDefault();
    const textArea = document.getElementById('sentimentTextInput');
    const resultBox = document.getElementById('sentimentResultBox');
    if (!textArea || !resultBox) return;

    const text = textArea.value.trim();
    if (!text) return;

    resultBox.innerHTML = `<div class="text-center small py-2" style="color: #cbd5e1;"><i class="fa-solid fa-spinner fa-spin me-2 text-info"></i> Menganalisis dengan kamus lexicon...</div>`;

    try {
        const res = await fetch(`/api/ai/sentiment?text=${encodeURIComponent(text)}`);
        const result = await res.json();

        if (result.success) {
            const label = escapeHtml(result.final_sentiment);
            const posCount = result.scores?.positive_words_found || 0;
            const negCount = result.scores?.negative_words_found || 0;
            const badgeClass = label === 'POSITIVE' ? 'bg-success' : label === 'NEGATIVE' ? 'bg-danger' : 'bg-secondary';

            resultBox.innerHTML = `
                <div class="p-3 rounded" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.18);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-bold" style="color: #e2e8f0;">Klasifikasi AI:</span>
                        <span class="badge ${badgeClass} text-white fw-bold px-2 py-1">${label}</span>
                    </div>
                    <div class="d-flex justify-content-around text-center mt-2 pt-2" style="border-top: 1px solid rgba(255,255,255,0.12);">
                        <div>
                            <div class="small text-success fw-bold">${posCount}</div>
                            <div class="small" style="color: #cbd5e1; font-size: 11px;">Kata Positif</div>
                        </div>
                        <div>
                            <div class="small text-danger fw-bold">${negCount}</div>
                            <div class="small" style="color: #cbd5e1; font-size: 11px;">Kata Negatif</div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultBox.innerHTML = `<div class="text-danger small">${escapeHtml(result.error || 'Terjadi kesalahan analisis.')}</div>`;
        }
    } catch (err) {
        resultBox.innerHTML = `<div class="text-danger small">Gagal terhubung ke engine sentimen.</div>`;
    }
}

/**
 * Handle Simulasi Skor Risiko
 */
async function handleRiskSimulation(e) {
    e.preventDefault();
    const windInput = document.getElementById('simWind');
    const infInput = document.getElementById('simInf');
    const newsInput = document.getElementById('simNews');
    const currInput = document.getElementById('simCurr');
    const outputBox = document.getElementById('simResultBox');

    if (!windInput || !outputBox) return;

    try {
        const res = await fetch(`/api/ai/predict-risk?wind=${windInput.value}&inflation=${infInput?.value || 2}&news=${newsInput?.value || 50}&currency=${currInput?.value || 10}`);
        const result = await res.json();

        if (result.success && result.prediction) {
            const score = result.prediction.total_risk_score;
            const status = escapeHtml(result.prediction.risk_status);
            const badgeClass = status.includes('HIGH') ? 'bg-danger' : status.includes('MEDIUM') ? 'bg-warning text-dark' : 'bg-success';

            outputBox.innerHTML = `
                <div class="mt-3 p-3 rounded text-center" style="background: rgba(200,156,98,0.15); border: 1px solid var(--accent-gold);">
                    <div class="small fw-bold mb-1" style="color: #e2e8f0;">Hasil Simulasi Risiko Berbobot</div>
                    <h4 class="fw-bold text-white mb-1">${score} / 100</h4>
                    <span class="badge ${badgeClass} text-white fw-bold px-3 py-1">${status}</span>
                </div>
            `;
        }
    } catch (err) {
        outputBox.innerHTML = `<div class="text-danger small mt-2">Gagal menjalankan simulasi.</div>`;
    }
}

/**
 * ============================================================================
 * WATCHLIST / FAVORITE MONITORING (PERSISTED KE DATABASE SERVER VIA AJAX)
 * ============================================================================
 */
async function loadWatchlistsFromServer() {
    const listContainer = document.getElementById('watchlistContainer');
    if (!listContainer) return;

    try {
        const res = await fetch('/api/watchlist');
        const result = await res.json();

        if (result.success && Array.isArray(result.data) && result.data.length > 0) {
            listContainer.innerHTML = result.data.map(item => {
                const safeName = escapeHtml(item.country_name);
                const safeCurr = escapeHtml(item.currency);
                const safeRegion = escapeHtml(item.region || 'Global');
                const safeId = item.id;
                return `
                    <div class="watchlist-item p-3.5 mb-3 rounded-3 shadow-sm glass-card" style="border-left: 4px solid var(--primary);">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2.5 pb-2 border-bottom">
                            <div class="d-flex align-items-center" style="cursor: pointer;" onclick="selectCountry('${safeName}')">
                                <i class="fa-solid fa-star text-warning me-2 fs-5"></i>
                                <div>
                                    <span class="fw-bold text-dark fs-6 me-2">${safeName}</span>
                                    <span class="badge badge-soft-secondary px-2 py-0.5 me-2" style="font-size: 11px;">${safeRegion}</span>
                                    <span class="badge badge-soft-info px-2 py-0.5" style="font-size: 11px;">Currency: ${safeCurr}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                <button class="btn btn-sm btn-outline-danger px-2.5 py-1" onclick="removeFromWatchlist(${safeId})" title="Remove from Watchlist" style="font-size: 12px;">
                                    <i class="fa-solid fa-trash-can me-1"></i> Remove
                                </button>
                            </div>
                        </div>

                        <!-- 4 Compact Indicator Cards Grid -->
                        <div class="row g-2 pt-1">
                            <!-- 1. Maritime Weather -->
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="p-2.5 rounded-2 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="small fw-semibold text-muted" style="font-size: 11px;"><i class="fa-solid fa-cloud-bolt text-primary me-1"></i> Weather</span>
                                        <i class="fa-solid fa-water text-primary" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="my-1">
                                        <div id="wl_weather_temp_${safeId}" class="fw-bold text-dark mb-0" style="font-size: 16px;">-- °C</div>
                                        <div class="d-flex justify-content-between small mt-1 text-muted" style="font-size: 11px;">
                                            <span>Wind: <strong id="wl_weather_wind_${safeId}" class="text-dark">-- m/s</strong></span>
                                            <span>Humidity: <strong id="wl_weather_hum_${safeId}" class="text-dark">-- %</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Economic Metrics -->
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="p-2.5 rounded-2 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="small fw-semibold text-muted" style="font-size: 11px;"><i class="fa-solid fa-chart-line text-success me-1"></i> Economy</span>
                                        <i class="fa-solid fa-building-columns text-success" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="my-1">
                                        <div id="wl_econ_gdp_${safeId}" class="fw-bold text-dark mb-0" style="font-size: 16px;">--</div>
                                        <div class="d-flex justify-content-between small mt-1 text-muted" style="font-size: 11px;">
                                            <span>Inflation: <strong id="wl_econ_inf_${safeId}" class="text-warning">-- %</strong></span>
                                            <span>Pop: <strong id="wl_econ_pop_${safeId}" class="text-dark">--</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Exchange Rate -->
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="p-2.5 rounded-2 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="small fw-semibold text-muted" style="font-size: 11px;"><i class="fa-solid fa-money-bill-transfer text-primary me-1"></i> Exchange Rate (${safeCurr})</span>
                                        <i class="fa-solid fa-coins text-primary" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="my-1">
                                        <div id="wl_curr_rate_${safeId}" class="fw-bold text-dark mb-0" style="font-size: 15px;">Loading...</div>
                                        <div class="small mt-1 text-truncate text-muted" style="font-size: 11px;">Base: USD &rarr; Target: ${safeCurr}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. AI Risk Score -->
                            <div class="col-12 col-sm-6 col-xl-3">
                                <div class="p-2.5 rounded-2 h-100 d-flex flex-column justify-content-between" style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 3px solid var(--primary);">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="small fw-semibold text-muted" style="font-size: 11px;"><i class="fa-solid fa-shield-halved text-primary me-1"></i> Risk Score</span>
                                        <i class="fa-solid fa-robot text-primary" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="my-1 d-flex align-items-center justify-content-between">
                                        <div>
                                            <span id="wl_risk_score_${safeId}" class="fw-bold text-dark mb-0" style="font-size: 16px;">--</span>
                                            <span class="small text-muted" style="font-size: 11px;">/ 100</span>
                                        </div>
                                        <div>
                                            <span id="wl_risk_badge_${safeId}" class="badge badge-soft-secondary px-2 py-1 fw-semibold" style="font-size: 10px;">ANALYZING</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            populateWatchlistMetrics(result.data);
        } else {
            listContainer.innerHTML = `<div class="small text-center p-3" style="color: #cbd5e1;">Belum ada negara yang dipantau. Klik tombol di bawah untuk menambahkan.</div>`;
        }
    } catch (e) {
        console.error("Gagal memuat Watchlist:", e);
    }
}

async function populateWatchlistMetrics(items) {
    if (!Array.isArray(items) || items.length === 0) return;

    let exchangeRates = null;
    try {
        const currRes = await fetch('/api/external/currency/USD');
        const currData = await currRes.json();
        if (currData.success && currData.rates) {
            exchangeRates = currData.rates;
        }
    } catch (e) {
        console.error("WL currency batch error:", e);
    }

    for (const item of items) {
        const safeId = item.id;
        const countryName = item.country_name;
        const meta = countryMetadata[countryName] || { iso: 'UN', currency: item.currency || 'USD', lat: 0, lng: 0, region: item.region || 'Global' };

        // 1. Kurs Valuta
        if (exchangeRates) {
            const targetCurr = meta.currency || item.currency || 'USD';
            const rate = exchangeRates[targetCurr] || 1;
            const rateEl = document.getElementById(`wl_curr_rate_${safeId}`);
            if (rateEl) rateEl.textContent = `1 USD = ${rate.toLocaleString('en-US', { maximumFractionDigits: 2 })} ${escapeHtml(targetCurr)}`;
        } else {
            const rateEl = document.getElementById(`wl_curr_rate_${safeId}`);
            if (rateEl) rateEl.textContent = `1 USD = 1.00 ${escapeHtml(meta.currency || 'USD')}`;
        }

        // 2. Cuaca Maritim (Open-Meteo)
        fetch(`/api/external/weather/${meta.lat}/${meta.lng}`)
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data) {
                    const tempEl = document.getElementById(`wl_weather_temp_${safeId}`);
                    const windEl = document.getElementById(`wl_weather_wind_${safeId}`);
                    const humEl = document.getElementById(`wl_weather_hum_${safeId}`);
                    if (tempEl) tempEl.textContent = `${result.data.temperature_2m ?? '--'} °C`;
                    if (windEl) windEl.textContent = `${result.data.wind_speed_10m ?? '--'} m/s`;
                    if (humEl) humEl.textContent = `${result.data.humidity ?? 78} %`;
                }
            }).catch(e => console.error(`WL weather error (${countryName}):`, e));

        // 3. Metrik Ekonomi (World Bank)
        fetch(`/api/external/economy/${meta.iso}`)
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data) {
                    const gdpEl = document.getElementById(`wl_econ_gdp_${safeId}`);
                    const infEl = document.getElementById(`wl_econ_inf_${safeId}`);
                    const popEl = document.getElementById(`wl_econ_pop_${safeId}`);
                    if (gdpEl && result.data.gdp) {
                        const gdpVal = result.data.gdp;
                        gdpEl.textContent = gdpVal >= 1e12 ? `$${(gdpVal / 1e12).toFixed(2)} Trillion` : `$${(gdpVal / 1e9).toFixed(2)} Billion`;
                    }
                    if (infEl && result.data.inflation !== undefined && result.data.inflation !== null) infEl.textContent = `${result.data.inflation.toFixed(1)} %`;
                    if (popEl && result.data.population) popEl.textContent = `${(result.data.population / 1e6).toFixed(1)} M`;
                }
            }).catch(e => console.error(`WL economy error (${countryName}):`, e));

        // 4. Skor Risiko Rantai Pasok AI
        fetch(`/api/ai/predict-risk?country=${encodeURIComponent(countryName)}&iso=${encodeURIComponent(meta.iso)}&wind=12&inflation=3.5&exchange=150`)
            .then(res => res.json())
            .then(result => {
                if (result.success && result.prediction) {
                    const scoreEl = document.getElementById(`wl_risk_score_${safeId}`);
                    const badgeEl = document.getElementById(`wl_risk_badge_${safeId}`);
                    if (scoreEl) scoreEl.textContent = result.prediction.total_risk_score;
                    if (badgeEl) {
                        badgeEl.textContent = escapeHtml(result.prediction.risk_status);
                        badgeEl.className = 'badge px-2 py-1 fw-bold text-white ' + (
                            result.prediction.risk_status.includes('HIGH') ? 'bg-danger' :
                            result.prediction.risk_status.includes('MEDIUM') ? 'bg-warning text-dark' : 'bg-success'
                        );
                    }
                }
            }).catch(e => console.error(`WL risk error (${countryName}):`, e));
    }
}

async function addToWatchlist() {
    const countryName = activeCountryData.name;
    const currency = activeCountryData.currency;
    const region = activeCountryData.region;

    if (!countryName || countryName === 'Belum Dipilih') {
        alert('Silakan pilih atau cari negara terlebih dahulu sebelum menambahkan ke favorit.');
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('/api/watchlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ country_name: countryName, currency: currency, region: region })
        });

        const result = await res.json();
        if (result.success) {
            await loadWatchlistsFromServer();
            const btn = document.getElementById('addFavoriteBtn') || document.getElementById('addToWatchlistBtn');
            if (btn) {
                const origHtml = btn.innerHTML;
                btn.classList.remove('btn-outline-warning');
                btn.classList.add('btn-success', 'text-white');
                btn.innerHTML = `<i class="fa-solid fa-check me-1"></i> <span>Tersimpan!</span>`;
                setTimeout(() => {
                    btn.classList.remove('btn-success', 'text-white');
                    btn.classList.add('btn-outline-warning');
                    btn.innerHTML = origHtml;
                }, 2500);
            }
            alert(`Negara "${countryName}" berhasil ditambahkan ke Daftar Pantauan Favorit! Anda dapat melihatnya langsung di menu Watchlist Favorit.`);
        } else {
            alert(result.message || 'Gagal menambahkan ke pantauan.');
        }
    } catch (e) {
        console.error("Gagal menambahkan Watchlist:", e);
    }
}

async function removeFromWatchlist(id) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(`/api/watchlist/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const result = await res.json();
        if (result.success) {
            await loadWatchlistsFromServer();
        }
    } catch (e) {
        console.error("Gagal menghapus Watchlist:", e);
    }
}

// Globalisasi fungsi agar bisa dipanggil onclick dari HTML
window.selectCountry = selectCountry;
window.addToWatchlist = addToWatchlist;
window.removeFromWatchlist = removeFromWatchlist;
