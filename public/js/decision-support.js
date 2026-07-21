/**
 * AI Decision Support Center (`/decision-support`) - ES6 Module / Vanilla JS
 * Section: Country Comparison Engine (Compact 1 Unified Table with 195 Searchable Countries)
 */

// Database Profil 195 Negara Berdaulat Dunia (UN Sovereign States)
const GLOBAL_195_COUNTRIES = {
    'Afghanistan': { iso: 'AF', currency: 'AFN - Afghan Afghani', region: 'Asia', gdp_val: '$14.3 Billion', gdp_growth: '1.2%', gdp_num: 1.2, inf_rate: '4.5%', inf_score: 52, risk_score: 68, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Dry Continental Wind', wind: '16 knots', rain: '5 mm/hr' },
    'Albania': { iso: 'AL', currency: 'ALL - Albanian Lek', region: 'Europe', gdp_val: '$18.9 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '2.4%', inf_score: 24, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Mediterranean Breeze', wind: '12 knots', rain: '10 mm/hr' },
    'Algeria': { iso: 'DZ', currency: 'DZD - Algerian Dinar', region: 'Africa', gdp_val: '$195.4 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '4.1%', inf_score: 41, risk_score: 45, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Arid Coastal Air', wind: '14 knots', rain: '4 mm/hr' },
    'Andorra': { iso: 'AD', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$3.3 Billion', gdp_growth: '1.8%', gdp_num: 1.8, inf_rate: '2.2%', inf_score: 22, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Mountain Clear', wind: '8 knots', rain: '12 mm/hr' },
    'Angola': { iso: 'AO', currency: 'AOA - Angolan Kwanza', region: 'Africa', gdp_val: '$106.8 Billion', gdp_growth: '1.9%', gdp_num: 1.9, inf_rate: '13.5%', inf_score: 65, risk_score: 55, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Tropical Maritime', wind: '15 knots', rain: '22 mm/hr' },
    'Antigua and Barbuda': { iso: 'AG', currency: 'XCD - East Caribbean Dollar', region: 'Americas', gdp_val: '$1.8 Billion', gdp_growth: '4.2%', gdp_num: 4.2, inf_rate: '2.9%', inf_score: 29, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Trade Winds', wind: '16 knots', rain: '15 mm/hr' },
    'Argentina': { iso: 'AR', currency: 'ARS - Argentine Peso', region: 'Americas', gdp_val: '$641.1 Billion', gdp_growth: '0.8%', gdp_num: 0.8, inf_rate: '54.2%', inf_score: 88, risk_score: 60, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Temperate Atlantic', wind: '18 knots', rain: '14 mm/hr' },
    'Armenia': { iso: 'AM', currency: 'AMD - Armenian Dram', region: 'Asia', gdp_val: '$24.2 Billion', gdp_growth: '5.8%', gdp_num: 5.8, inf_rate: '1.8%', inf_score: 18, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Continental Highland', wind: '11 knots', rain: '8 mm/hr' },
    'Australia': { iso: 'AU', currency: 'AUD - Australian Dollar', region: 'Oceania', gdp_val: '$1.69 Trillion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '3.8%', inf_score: 28, risk_score: 35, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Seasonal Coastal Breezes', wind: '18 knots', rain: '24 mm/hr' },
    'Austria': { iso: 'AT', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$471.4 Billion', gdp_growth: '1.1%', gdp_num: 1.1, inf_rate: '2.6%', inf_score: 26, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Alpine Calm', wind: '10 knots', rain: '12 mm/hr' },
    'Azerbaijan': { iso: 'AZ', currency: 'AZN - Azerbaijani Manat', region: 'Asia', gdp_val: '$78.7 Billion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '3.2%', inf_score: 32, risk_score: 40, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Caspian Sea Breeze', wind: '17 knots', rain: '9 mm/hr' },
    'Bahamas': { iso: 'BS', currency: 'BSD - Bahamian Dollar', region: 'Americas', gdp_val: '$14.3 Billion', gdp_growth: '2.3%', gdp_num: 2.3, inf_rate: '2.8%', inf_score: 28, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Atlantic Island Calm', wind: '15 knots', rain: '18 mm/hr' },
    'Bahrain': { iso: 'BH', currency: 'BHD - Bahraini Dinar', region: 'Asia', gdp_val: '$44.9 Billion', gdp_growth: '3.0%', gdp_num: 3.0, inf_rate: '1.5%', inf_score: 15, risk_score: 25, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Persian Gulf Calm', wind: '12 knots', rain: '2 mm/hr' },
    'Bangladesh': { iso: 'BD', currency: 'BDT - Bangladeshi Taka', region: 'Asia', gdp_val: '$455.2 Billion', gdp_growth: '5.6%', gdp_num: 5.6, inf_rate: '8.4%', inf_score: 58, risk_score: 54, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Bay of Bengal Monsoon', wind: '20 knots', rain: '35 mm/hr' },
    'Barbados': { iso: 'BB', currency: 'BBD - Barbadian Dollar', region: 'Americas', gdp_val: '$6.3 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '3.1%', inf_score: 31, risk_score: 27, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Tropical Trade Winds', wind: '16 knots', rain: '14 mm/hr' },
    'Belarus': { iso: 'BY', currency: 'BYN - Belarusian Ruble', region: 'Europe', gdp_val: '$71.8 Billion', gdp_growth: '1.4%', gdp_num: 1.4, inf_rate: '5.8%', inf_score: 58, risk_score: 65, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Continental Clear', wind: '12 knots', rain: '14 mm/hr' },
    'Belgium': { iso: 'BE', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$627.5 Billion', gdp_growth: '1.3%', gdp_num: 1.3, inf_rate: '2.4%', inf_score: 24, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'North Sea Transit', wind: '16 knots', rain: '16 mm/hr' },
    'Belize': { iso: 'BZ', currency: 'BZD - Belize Dollar', region: 'Americas', gdp_val: '$3.2 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '2.7%', inf_score: 27, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Caribbean Coastal', wind: '14 knots', rain: '19 mm/hr' },
    'Benin': { iso: 'BJ', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$19.6 Billion', gdp_growth: '5.2%', gdp_num: 5.2, inf_rate: '2.8%', inf_score: 28, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Guinea Calm', wind: '12 knots', rain: '20 mm/hr' },
    'Bhutan': { iso: 'BT', currency: 'BTN - Bhutanese Ngultrum', region: 'Asia', gdp_val: '$2.9 Billion', gdp_growth: '4.6%', gdp_num: 4.6, inf_rate: '4.2%', inf_score: 42, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Himalayan Mountain Air', wind: '9 knots', rain: '15 mm/hr' },
    'Bolivia': { iso: 'BO', currency: 'BOB - Boliviano', region: 'Americas', gdp_val: '$45.8 Billion', gdp_growth: '2.1%', gdp_num: 2.1, inf_rate: '2.9%', inf_score: 29, risk_score: 45, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Highland Continental', wind: '11 knots', rain: '11 mm/hr' },
    'Bosnia and Herzegovina': { iso: 'BA', currency: 'BAM - Convertible Mark', region: 'Europe', gdp_val: '$27.1 Billion', gdp_growth: '2.3%', gdp_num: 2.3, inf_rate: '2.5%', inf_score: 25, risk_score: 36, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Balkan Continental', wind: '12 knots', rain: '13 mm/hr' },
    'Botswana': { iso: 'BW', currency: 'BWP - Pula', region: 'Africa', gdp_val: '$19.3 Billion', gdp_growth: '3.7%', gdp_num: 3.7, inf_rate: '3.1%', inf_score: 31, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Kalahari Dry Air', wind: '14 knots', rain: '5 mm/hr' },
    'Brazil': { iso: 'BR', currency: 'BRL - Brazilian Real', region: 'Americas', gdp_val: '$2.17 Trillion', gdp_growth: '2.9%', gdp_num: 2.9, inf_rate: '4.2%', inf_score: 42, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'South Atlantic Maritime', wind: '16 knots', rain: '28 mm/hr' },
    'Brunei': { iso: 'BN', currency: 'BND - Brunei Dollar', region: 'Asia', gdp_val: '$15.1 Billion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '1.2%', inf_score: 12, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'South China Sea Breeze', wind: '11 knots', rain: '25 mm/hr' },
    'Bulgaria': { iso: 'BG', currency: 'BGN - Bulgarian Lev', region: 'Europe', gdp_val: '$101.6 Billion', gdp_growth: '2.2%', gdp_num: 2.2, inf_rate: '3.4%', inf_score: 34, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Black Sea Coastal', wind: '14 knots', rain: '12 mm/hr' },
    'Burkina Faso': { iso: 'BF', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$20.3 Billion', gdp_growth: '3.6%', gdp_num: 3.6, inf_rate: '3.8%', inf_score: 38, risk_score: 58, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Sahelian Dry Breeze', wind: '15 knots', rain: '6 mm/hr' },
    'Burundi': { iso: 'BI', currency: 'BIF - Burundian Franc', region: 'Africa', gdp_val: '$3.2 Billion', gdp_growth: '2.7%', gdp_num: 2.7, inf_rate: '12.1%', inf_score: 65, risk_score: 62, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'East African Highland', wind: '10 knots', rain: '18 mm/hr' },
    'Cabo Verde': { iso: 'CV', currency: 'CVE - Cabo Verdean Escudo', region: 'Africa', gdp_val: '$2.5 Billion', gdp_growth: '4.8%', gdp_num: 4.8, inf_rate: '2.1%', inf_score: 21, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Atlantic Trade Winds', wind: '18 knots', rain: '5 mm/hr' },
    'Cambodia': { iso: 'KH', currency: 'KHR - Cambodian Riel', region: 'Asia', gdp_val: '$31.8 Billion', gdp_growth: '5.8%', gdp_num: 5.8, inf_rate: '2.6%', inf_score: 26, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Thailand Breeze', wind: '12 knots', rain: '26 mm/hr' },
    'Cameroon': { iso: 'CM', currency: 'XAF - Central African CFA Franc', region: 'Africa', gdp_val: '$49.3 Billion', gdp_growth: '3.9%', gdp_num: 3.9, inf_rate: '4.3%', inf_score: 43, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'West African Maritime', wind: '13 knots', rain: '30 mm/hr' },
    'Canada': { iso: 'CA', currency: 'CAD - Canadian Dollar', region: 'Americas', gdp_val: '$2.14 Trillion', gdp_growth: '1.5%', gdp_num: 1.5, inf_rate: '2.8%', inf_score: 28, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'North Atlantic Clear', wind: '17 knots', rain: '15 mm/hr' },
    'Central African Republic': { iso: 'CF', currency: 'XAF - Central African CFA Franc', region: 'Africa', gdp_val: '$2.7 Billion', gdp_growth: '1.8%', gdp_num: 1.8, inf_rate: '5.6%', inf_score: 56, risk_score: 68, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Tropical Continental', wind: '11 knots', rain: '18 mm/hr' },
    'Chad': { iso: 'TD', currency: 'XAF - Central African CFA Franc', region: 'Africa', gdp_val: '$18.1 Billion', gdp_growth: '3.2%', gdp_num: 3.2, inf_rate: '4.9%', inf_score: 49, risk_score: 62, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Saharan Dry Winds', wind: '16 knots', rain: '3 mm/hr' },
    'Chile': { iso: 'CL', currency: 'CLP - Chilean Peso', region: 'Americas', gdp_val: '$335.5 Billion', gdp_growth: '2.0%', gdp_num: 2.0, inf_rate: '3.5%', inf_score: 35, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Pacific Coastal Breezes', wind: '16 knots', rain: '14 mm/hr' },
    'China': { iso: 'CN', currency: 'CNY - Chinese Yuan', region: 'Asia', gdp_val: '$18.53 Trillion', gdp_growth: '5.2%', gdp_num: 5.2, inf_rate: '0.8%', inf_score: 18, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Yellow Sea Transit Watch', wind: '15 knots', rain: '18 mm/hr' },
    'Colombia': { iso: 'CO', currency: 'COP - Colombian Peso', region: 'Americas', gdp_val: '$363.5 Billion', gdp_growth: '1.6%', gdp_num: 1.6, inf_rate: '5.1%', inf_score: 51, risk_score: 44, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Caribbean & Pacific Ports', wind: '14 knots', rain: '24 mm/hr' },
    'Comoros': { iso: 'KM', currency: 'KMF - Comorian Franc', region: 'Africa', gdp_val: '$1.4 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '3.8%', inf_score: 38, risk_score: 46, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Indian Ocean Coastal', wind: '16 knots', rain: '22 mm/hr' },
    'Congo (Brazzaville)': { iso: 'CG', currency: 'XAF - Central African CFA Franc', region: 'Africa', gdp_val: '$15.3 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '3.2%', inf_score: 32, risk_score: 52, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Equatorial Atlantic', wind: '12 knots', rain: '26 mm/hr' },
    'Congo (Kinshasa)': { iso: 'CD', currency: 'CDF - Congolese Franc', region: 'Africa', gdp_val: '$67.5 Billion', gdp_growth: '6.2%', gdp_num: 6.2, inf_rate: '15.2%', inf_score: 72, risk_score: 64, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Congo River Basin Calm', wind: '10 knots', rain: '28 mm/hr' },
    'Costa Rica': { iso: 'CR', currency: 'CRC - Costa Rican Colon', region: 'Americas', gdp_val: '$86.5 Billion', gdp_growth: '4.1%', gdp_num: 4.1, inf_rate: '1.9%', inf_score: 19, risk_score: 25, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Trade Winds', wind: '15 knots', rain: '22 mm/hr' },
    'Croatia': { iso: 'HR', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$82.6 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '3.1%', inf_score: 31, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Adriatic Coastal Calm', wind: '13 knots', rain: '11 mm/hr' },
    'Cuba': { iso: 'CU', currency: 'CUP - Cuban Peso', region: 'Americas', gdp_val: '$107.4 Billion', gdp_growth: '1.1%', gdp_num: 1.1, inf_rate: '18.5%', inf_score: 75, risk_score: 58, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Caribbean Maritime Watch', wind: '16 knots', rain: '18 mm/hr' },
    'Cyprus': { iso: 'CY', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$32.2 Billion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '2.2%', inf_score: 22, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Eastern Mediterranean Calm', wind: '14 knots', rain: '8 mm/hr' },
    'Czechia': { iso: 'CZ', currency: 'CZK - Czech Koruna', region: 'Europe', gdp_val: '$330.8 Billion', gdp_growth: '1.6%', gdp_num: 1.6, inf_rate: '2.5%', inf_score: 25, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Central European Clear', wind: '12 knots', rain: '12 mm/hr' },
    'Denmark': { iso: 'DK', currency: 'DKK - Danish Krone', region: 'Europe', gdp_val: '$404.2 Billion', gdp_growth: '1.9%', gdp_num: 1.9, inf_rate: '1.8%', inf_score: 18, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic & North Sea Breeze', wind: '17 knots', rain: '15 mm/hr' },
    'Djibouti': { iso: 'DJ', currency: 'DJF - Djiboutian Franc', region: 'Africa', gdp_val: '$4.1 Billion', gdp_growth: '5.1%', gdp_num: 5.1, inf_rate: '2.4%', inf_score: 24, risk_score: 35, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Red Sea Gulf Calm', wind: '15 knots', rain: '4 mm/hr' },
    'Dominica': { iso: 'DM', currency: 'XCD - East Caribbean Dollar', region: 'Americas', gdp_val: '$0.68 Billion', gdp_growth: '4.5%', gdp_num: 4.5, inf_rate: '2.8%', inf_score: 28, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Island Breeze', wind: '16 knots', rain: '20 mm/hr' },
    'Dominican Republic': { iso: 'DO', currency: 'DOP - Dominican Peso', region: 'Americas', gdp_val: '$121.4 Billion', gdp_growth: '4.8%', gdp_num: 4.8, inf_rate: '3.6%', inf_score: 36, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Caribbean Maritime Watch', wind: '15 knots', rain: '17 mm/hr' },
    'Ecuador': { iso: 'EC', currency: 'USD - US Dollar', region: 'Americas', gdp_val: '$118.8 Billion', gdp_growth: '1.8%', gdp_num: 1.8, inf_rate: '1.9%', inf_score: 19, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Pacific Coastal Waters', wind: '13 knots', rain: '22 mm/hr' },
    'Egypt': { iso: 'EG', currency: 'EGP - Egyptian Pound', region: 'Africa', gdp_val: '$395.9 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '25.4%', inf_score: 78, risk_score: 52, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Suez Canal Transit Calm', wind: '14 knots', rain: '2 mm/hr' },
    'El Salvador': { iso: 'SV', currency: 'USD - US Dollar', region: 'Americas', gdp_val: '$34.0 Billion', gdp_growth: '2.7%', gdp_num: 2.7, inf_rate: '1.6%', inf_score: 16, risk_score: 34, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Pacific Port Coastal', wind: '12 knots', rain: '18 mm/hr' },
    'Equatorial Guinea': { iso: 'GQ', currency: 'XAF - Central African CFA Franc', region: 'Africa', gdp_val: '$12.1 Billion', gdp_growth: '0.9%', gdp_num: 0.9, inf_rate: '3.2%', inf_score: 32, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Guinea Maritime', wind: '11 knots', rain: '26 mm/hr' },
    'Eritrea': { iso: 'ER', currency: 'ERN - Eritrean Nakfa', region: 'Africa', gdp_val: '$2.6 Billion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '6.5%', inf_score: 65, risk_score: 58, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Red Sea Coastal Air', wind: '16 knots', rain: '5 mm/hr' },
    'Estonia': { iso: 'EE', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$40.7 Billion', gdp_growth: '1.2%', gdp_num: 1.2, inf_rate: '3.4%', inf_score: 34, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic Sea Coastal', wind: '16 knots', rain: '14 mm/hr' },
    'Eswatini': { iso: 'SZ', currency: 'SZL - Swazi Lilangeni', region: 'Africa', gdp_val: '$4.9 Billion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '4.2%', inf_score: 42, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Highland Dry Air', wind: '12 knots', rain: '10 mm/hr' },
    'Ethiopia': { iso: 'ET', currency: 'ETB - Ethiopian Birr', region: 'Africa', gdp_val: '$163.7 Billion', gdp_growth: '6.1%', gdp_num: 6.1, inf_rate: '28.1%', inf_score: 82, risk_score: 56, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Horn of Africa Highs', wind: '13 knots', rain: '14 mm/hr' },
    'Fiji': { iso: 'FJ', currency: 'FJD - Fijian Dollar', region: 'Oceania', gdp_val: '$5.5 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '2.4%', inf_score: 24, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'South Pacific Trade Winds', wind: '17 knots', rain: '24 mm/hr' },
    'Finland': { iso: 'FI', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$300.2 Billion', gdp_growth: '1.1%', gdp_num: 1.1, inf_rate: '1.9%', inf_score: 19, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic Gulf Breeze', wind: '15 knots', rain: '12 mm/hr' },
    'France': { iso: 'FR', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$3.03 Trillion', gdp_growth: '1.3%', gdp_num: 1.3, inf_rate: '2.5%', inf_score: 25, risk_score: 21, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Atlantic & Mediterranean Ports', wind: '16 knots', rain: '15 mm/hr' },
    'Gabon': { iso: 'GA', currency: 'XAF - Central African CFA Franc', region: 'Africa', gdp_val: '$21.0 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '3.4%', inf_score: 34, risk_score: 45, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Equatorial Maritime', wind: '12 knots', rain: '25 mm/hr' },
    'Gambia': { iso: 'GM', currency: 'GMD - Gambian Dalasi', region: 'Africa', gdp_val: '$2.3 Billion', gdp_growth: '5.3%', gdp_num: 5.3, inf_rate: '7.2%', inf_score: 52, risk_score: 44, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Atlantic Coastal Air', wind: '14 knots', rain: '16 mm/hr' },
    'Georgia': { iso: 'GE', currency: 'GEL - Georgian Lari', region: 'Asia', gdp_val: '$30.5 Billion', gdp_growth: '6.5%', gdp_num: 6.5, inf_rate: '1.5%', inf_score: 15, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Black Sea Coastal Calm', wind: '14 knots', rain: '14 mm/hr' },
    'Germany': { iso: 'DE', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$4.46 Trillion', gdp_growth: '0.9%', gdp_num: 0.9, inf_rate: '3.2%', inf_score: 42, risk_score: 25, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Clear Skies & Calm Maritime', wind: '14 knots', rain: '12 mm/hr' },
    'Ghana': { iso: 'GH', currency: 'GHS - Ghanaian Cedi', region: 'Africa', gdp_val: '$76.4 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '23.2%', inf_score: 75, risk_score: 50, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Guinea Trade Breeze', wind: '14 knots', rain: '18 mm/hr' },
    'Greece': { iso: 'GR', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$238.2 Billion', gdp_growth: '2.2%', gdp_num: 2.2, inf_rate: '2.8%', inf_score: 28, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Aegean & Mediterranean Calm', wind: '15 knots', rain: '10 mm/hr' },
    'Grenada': { iso: 'GD', currency: 'XCD - East Caribbean Dollar', region: 'Americas', gdp_val: '$1.3 Billion', gdp_growth: '4.1%', gdp_num: 4.1, inf_rate: '2.5%', inf_score: 25, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Island Breeze', wind: '16 knots', rain: '16 mm/hr' },
    'Guatemala': { iso: 'GT', currency: 'GTQ - Guatemalan Quetzal', region: 'Americas', gdp_val: '$102.3 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '3.8%', inf_score: 38, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Pacific & Caribbean Ports', wind: '13 knots', rain: '20 mm/hr' },
    'Guinea': { iso: 'GN', currency: 'GNF - Guinean Franc', region: 'Africa', gdp_val: '$23.6 Billion', gdp_growth: '5.6%', gdp_num: 5.6, inf_rate: '8.6%', inf_score: 56, risk_score: 55, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Atlantic Coastal Monsoon', wind: '14 knots', rain: '26 mm/hr' },
    'Guinea-Bissau': { iso: 'GW', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$1.9 Billion', gdp_growth: '4.2%', gdp_num: 4.2, inf_rate: '4.5%', inf_score: 45, risk_score: 52, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Tropical Coastal Air', wind: '13 knots', rain: '24 mm/hr' },
    'Guyana': { iso: 'GY', currency: 'GYD - Guyanese Dollar', region: 'Americas', gdp_val: '$16.3 Billion', gdp_growth: '26.8%', gdp_num: 26.8, inf_rate: '3.2%', inf_score: 32, risk_score: 35, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Atlantic Coastal Breezes', wind: '15 knots', rain: '22 mm/hr' },
    'Haiti': { iso: 'HT', currency: 'HTG - Haitian Gourde', region: 'Americas', gdp_val: '$19.8 Billion', gdp_growth: '0.2%', gdp_num: 0.2, inf_rate: '28.5%', inf_score: 85, risk_score: 75, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Caribbean Storm Watch', wind: '16 knots', rain: '20 mm/hr' },
    'Honduras': { iso: 'HN', currency: 'HNL - Honduran Lempira', region: 'Americas', gdp_val: '$34.4 Billion', gdp_growth: '3.6%', gdp_num: 3.6, inf_rate: '4.8%', inf_score: 48, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Caribbean Coastal Breezes', wind: '14 knots', rain: '21 mm/hr' },
    'Hungary': { iso: 'HU', currency: 'HUF - Hungarian Forint', region: 'Europe', gdp_val: '$212.4 Billion', gdp_growth: '1.8%', gdp_num: 1.8, inf_rate: '3.8%', inf_score: 38, risk_score: 25, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Continental European Clear', wind: '12 knots', rain: '13 mm/hr' },
    'Iceland': { iso: 'IS', currency: 'ISK - Icelandic Krona', region: 'Europe', gdp_val: '$31.0 Billion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '4.6%', inf_score: 46, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'North Atlantic Coastal', wind: '22 knots', rain: '18 mm/hr' },
    'India': { iso: 'IN', currency: 'INR - Indian Rupee', region: 'Asia', gdp_val: '$3.75 Trillion', gdp_growth: '6.8%', gdp_num: 6.8, inf_rate: '5.1%', inf_score: 51, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Indian Ocean Trade Winds', wind: '15 knots', rain: '25 mm/hr' },
    'Indonesia': { iso: 'ID', currency: 'IDR - Indonesian Rupiah', region: 'Asia', gdp_val: '$1.37 Trillion', gdp_growth: '5.0%', gdp_num: 5.0, inf_rate: '2.6%', inf_score: 26, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Tropical Archipelago Calm', wind: '12 knots', rain: '30 mm/hr' },
    'Iran': { iso: 'IR', currency: 'IRR - Iranian Rial', region: 'Asia', gdp_val: '$401.5 Billion', gdp_growth: '3.2%', gdp_num: 3.2, inf_rate: '39.5%', inf_score: 85, risk_score: 65, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Persian Gulf & Caspian Air', wind: '14 knots', rain: '6 mm/hr' },
    'Iraq': { iso: 'IQ', currency: 'IQD - Iraqi Dinar', region: 'Asia', gdp_val: '$250.8 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '4.2%', inf_score: 42, risk_score: 58, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Persian Gulf Northern Calm', wind: '14 knots', rain: '5 mm/hr' },
    'Ireland': { iso: 'IE', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$545.6 Billion', gdp_growth: '2.2%', gdp_num: 2.2, inf_rate: '2.4%', inf_score: 24, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'North Atlantic Breezes', wind: '18 knots', rain: '18 mm/hr' },
    'Israel': { iso: 'IL', currency: 'ILS - Israeli New Shekel', region: 'Asia', gdp_val: '$509.9 Billion', gdp_growth: '2.0%', gdp_num: 2.0, inf_rate: '2.8%', inf_score: 28, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Eastern Mediterranean Calm', wind: '14 knots', rain: '8 mm/hr' },
    'Italy': { iso: 'IT', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$2.25 Trillion', gdp_growth: '0.8%', gdp_num: 0.8, inf_rate: '1.8%', inf_score: 18, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Mediterranean Coastal Clear', wind: '13 knots', rain: '12 mm/hr' },
    'Ivory Coast': { iso: 'CI', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$78.8 Billion', gdp_growth: '6.6%', gdp_num: 6.6, inf_rate: '3.5%', inf_score: 35, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Guinea Trade Winds', wind: '13 knots', rain: '24 mm/hr' },
    'Jamaica': { iso: 'JM', currency: 'JMD - Jamaican Dollar', region: 'Americas', gdp_val: '$19.4 Billion', gdp_growth: '2.1%', gdp_num: 2.1, inf_rate: '5.2%', inf_score: 52, risk_score: 35, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Caribbean Island Breeze', wind: '16 knots', rain: '16 mm/hr' },
    'Japan': { iso: 'JP', currency: 'JPY - Japanese Yen', region: 'Asia', gdp_val: '$4.21 Trillion', gdp_growth: '1.0%', gdp_num: 1.0, inf_rate: '2.8%', inf_score: 28, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Pacific Coastal Breezes', wind: '16 knots', rain: '18 mm/hr' },
    'Jordan': { iso: 'JO', currency: 'JOD - Jordanian Dinar', region: 'Asia', gdp_val: '$50.8 Billion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '2.1%', inf_score: 21, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Red Sea Gulf Calm', wind: '13 knots', rain: '4 mm/hr' },
    'Kazakhstan': { iso: 'KZ', currency: 'KZT - Kazakhstani Tenge', region: 'Asia', gdp_val: '$261.4 Billion', gdp_growth: '4.5%', gdp_num: 4.5, inf_rate: '8.5%', inf_score: 58, risk_score: 40, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Caspian Sea Coastal Air', wind: '15 knots', rain: '8 mm/hr' },
    'Kenya': { iso: 'KE', currency: 'KES - Kenyan Shilling', region: 'Africa', gdp_val: '$107.4 Billion', gdp_growth: '5.1%', gdp_num: 5.1, inf_rate: '5.4%', inf_score: 54, risk_score: 45, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Indian Ocean Coastal Air', wind: '15 knots', rain: '18 mm/hr' },
    'Kiribati': { iso: 'KI', currency: 'AUD - Australian Dollar', region: 'Oceania', gdp_val: '$0.28 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '2.5%', inf_score: 25, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Equatorial Pacific Trade Winds', wind: '16 knots', rain: '22 mm/hr' },
    'Kuwait': { iso: 'KW', currency: 'KWD - Kuwaiti Dinar', region: 'Asia', gdp_val: '$161.8 Billion', gdp_growth: '1.6%', gdp_num: 1.6, inf_rate: '2.8%', inf_score: 28, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Persian Gulf Calm', wind: '14 knots', rain: '3 mm/hr' },
    'Kyrgyzstan': { iso: 'KG', currency: 'KGS - Kyrgyzstani Som', region: 'Asia', gdp_val: '$13.9 Billion', gdp_growth: '5.2%', gdp_num: 5.2, inf_rate: '6.2%', inf_score: 62, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Highland Mountain Clear', wind: '11 knots', rain: '10 mm/hr' },
    'Laos': { iso: 'LA', currency: 'LAK - Lao Kip', region: 'Asia', gdp_val: '$15.8 Billion', gdp_growth: '4.0%', gdp_num: 4.0, inf_rate: '24.5%', inf_score: 76, risk_score: 52, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Mekong Valley Inland Calm', wind: '10 knots', rain: '24 mm/hr' },
    'Latvia': { iso: 'LV', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$43.6 Billion', gdp_growth: '1.4%', gdp_num: 1.4, inf_rate: '2.9%', inf_score: 29, risk_score: 21, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic Sea Breeze', wind: '16 knots', rain: '14 mm/hr' },
    'Lebanon': { iso: 'LB', currency: 'LBP - Lebanese Pound', region: 'Asia', gdp_val: '$23.1 Billion', gdp_growth: '0.5%', gdp_num: 0.5, inf_rate: '58.0%', inf_score: 90, risk_score: 72, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Eastern Mediterranean Coastal', wind: '14 knots', rain: '10 mm/hr' },
    'Lesotho': { iso: 'LS', currency: 'LSL - Lesotho Loti', region: 'Africa', gdp_val: '$2.5 Billion', gdp_growth: '2.2%', gdp_num: 2.2, inf_rate: '5.8%', inf_score: 58, risk_score: 40, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Highland Mountain Air', wind: '13 knots', rain: '12 mm/hr' },
    'Liberia': { iso: 'LR', currency: 'LRD - Liberian Dollar', region: 'Africa', gdp_val: '$4.3 Billion', gdp_growth: '4.8%', gdp_num: 4.8, inf_rate: '8.2%', inf_score: 58, risk_score: 54, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Atlantic Coastal Monsoon', wind: '14 knots', rain: '28 mm/hr' },
    'Libya': { iso: 'LY', currency: 'LYD - Libyan Dinar', region: 'Africa', gdp_val: '$45.0 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '2.8%', inf_score: 28, risk_score: 62, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Mediterranean & Desert Coastal', wind: '16 knots', rain: '4 mm/hr' },
    'Liechtenstein': { iso: 'LI', currency: 'CHF - Swiss Franc', region: 'Europe', gdp_val: '$7.3 Billion', gdp_growth: '1.5%', gdp_num: 1.5, inf_rate: '1.4%', inf_score: 14, risk_score: 15, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Alpine Clear', wind: '9 knots', rain: '14 mm/hr' },
    'Lithuania': { iso: 'LT', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$77.8 Billion', gdp_growth: '1.6%', gdp_num: 1.6, inf_rate: '2.6%', inf_score: 26, risk_score: 21, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic Sea Coastal Air', wind: '16 knots', rain: '14 mm/hr' },
    'Luxembourg': { iso: 'LU', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$85.7 Billion', gdp_growth: '1.3%', gdp_num: 1.3, inf_rate: '2.2%', inf_score: 22, risk_score: 16, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Central European Clear', wind: '12 knots', rain: '15 mm/hr' },
    'Madagascar': { iso: 'MG', currency: 'MGA - Malagasy Ariary', region: 'Africa', gdp_val: '$16.0 Billion', gdp_growth: '4.2%', gdp_num: 4.2, inf_rate: '7.4%', inf_score: 54, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Indian Ocean Coastal Air', wind: '16 knots', rain: '26 mm/hr' },
    'Malawi': { iso: 'MW', currency: 'MWK - Malawian Kwacha', region: 'Africa', gdp_val: '$14.0 Billion', gdp_growth: '3.3%', gdp_num: 3.3, inf_rate: '28.5%', inf_score: 82, risk_score: 55, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Lake Malawi Continental', wind: '11 knots', rain: '18 mm/hr' },
    'Malaysia': { iso: 'MY', currency: 'MYR - Malaysian Ringgit', region: 'Asia', gdp_val: '$430.8 Billion', gdp_growth: '4.4%', gdp_num: 4.4, inf_rate: '1.8%', inf_score: 18, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Strait of Malacca Calm', wind: '12 knots', rain: '28 mm/hr' },
    'Maldives': { iso: 'MV', currency: 'MVR - Maldivian Rufiyaa', region: 'Asia', gdp_val: '$6.6 Billion', gdp_growth: '5.2%', gdp_num: 5.2, inf_rate: '2.6%', inf_score: 26, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Tropical Indian Ocean Breeze', wind: '15 knots', rain: '20 mm/hr' },
    'Mali': { iso: 'ML', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$20.9 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '3.2%', inf_score: 32, risk_score: 62, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Sahelian Dry Air', wind: '15 knots', rain: '5 mm/hr' },
    'Malta': { iso: 'MT', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$20.9 Billion', gdp_growth: '4.6%', gdp_num: 4.6, inf_rate: '2.8%', inf_score: 28, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Central Mediterranean Calm', wind: '15 knots', rain: '10 mm/hr' },
    'Marshall Islands': { iso: 'MH', currency: 'USD - US Dollar', region: 'Oceania', gdp_val: '$0.28 Billion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '2.8%', inf_score: 28, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Pacific Trade Winds', wind: '16 knots', rain: '22 mm/hr' },
    'Mauritania': { iso: 'MR', currency: 'MRU - Mauritanian Ouguiya', region: 'Africa', gdp_val: '$10.4 Billion', gdp_growth: '4.3%', gdp_num: 4.3, inf_rate: '4.8%', inf_score: 48, risk_score: 46, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Atlantic Desert Coastal Air', wind: '16 knots', rain: '3 mm/hr' },
    'Mauritius': { iso: 'MU', currency: 'MUR - Mauritian Rupee', region: 'Africa', gdp_val: '$14.8 Billion', gdp_growth: '4.5%', gdp_num: 4.5, inf_rate: '4.2%', inf_score: 42, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Indian Ocean Trade Winds', wind: '16 knots', rain: '18 mm/hr' },
    'Mexico': { iso: 'MX', currency: 'MXN - Mexican Peso', region: 'Americas', gdp_val: '$1.78 Trillion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '4.6%', inf_score: 46, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Gulf of Mexico & Pacific Ports', wind: '15 knots', rain: '18 mm/hr' },
    'Micronesia': { iso: 'FM', currency: 'USD - US Dollar', region: 'Oceania', gdp_val: '$0.46 Billion', gdp_growth: '2.1%', gdp_num: 2.1, inf_rate: '2.9%', inf_score: 29, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Western Pacific Island Air', wind: '15 knots', rain: '25 mm/hr' },
    'Moldova': { iso: 'MD', currency: 'MDL - Moldovan Leu', region: 'Europe', gdp_val: '$16.5 Billion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '4.8%', inf_score: 48, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Continental European Air', wind: '13 knots', rain: '12 mm/hr' },
    'Monaco': { iso: 'MC', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$8.6 Billion', gdp_growth: '1.8%', gdp_num: 1.8, inf_rate: '2.1%', inf_score: 21, risk_score: 15, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Mediterranean Riviera Calm', wind: '10 knots', rain: '12 mm/hr' },
    'Mongolia': { iso: 'MN', currency: 'MNT - Mongolian Tugrik', region: 'Asia', gdp_val: '$19.8 Billion', gdp_growth: '5.8%', gdp_num: 5.8, inf_rate: '6.8%', inf_score: 55, risk_score: 40, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Continental Steppe Winds', wind: '15 knots', rain: '6 mm/hr' },
    'Montenegro': { iso: 'ME', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$7.4 Billion', gdp_growth: '3.4%', gdp_num: 3.4, inf_rate: '3.2%', inf_score: 32, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Adriatic Coastal Calm', wind: '13 knots', rain: '14 mm/hr' },
    'Morocco': { iso: 'MA', currency: 'MAD - Moroccan Dirham', region: 'Africa', gdp_val: '$142.9 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '1.8%', inf_score: 18, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Strait of Gibraltar Coastal', wind: '15 knots', rain: '8 mm/hr' },
    'Mozambique': { iso: 'MZ', currency: 'MZN - Mozambican Metical', region: 'Africa', gdp_val: '$21.9 Billion', gdp_growth: '5.0%', gdp_num: 5.0, inf_rate: '4.2%', inf_score: 42, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Indian Ocean Coastal Air', wind: '16 knots', rain: '22 mm/hr' },
    'Myanmar': { iso: 'MM', currency: 'MMK - Myanmar Kyat', region: 'Asia', gdp_val: '$64.8 Billion', gdp_growth: '1.2%', gdp_num: 1.2, inf_rate: '18.4%', inf_score: 75, risk_score: 68, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Andaman Sea Monsoon Watch', wind: '16 knots', rain: '32 mm/hr' },
    'Namibia': { iso: 'NA', currency: 'NAD - Namibian Dollar', region: 'Africa', gdp_val: '$13.4 Billion', gdp_growth: '3.6%', gdp_num: 3.6, inf_rate: '4.5%', inf_score: 45, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'South Atlantic Coastal Air', wind: '16 knots', rain: '5 mm/hr' },
    'Nauru': { iso: 'NR', currency: 'AUD - Australian Dollar', region: 'Oceania', gdp_val: '$0.15 Billion', gdp_growth: '2.0%', gdp_num: 2.0, inf_rate: '2.8%', inf_score: 28, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Equatorial Pacific Trade Winds', wind: '16 knots', rain: '20 mm/hr' },
    'Nepal': { iso: 'NP', currency: 'NPR - Nepalese Rupee', region: 'Asia', gdp_val: '$41.3 Billion', gdp_growth: '3.9%', gdp_num: 3.9, inf_rate: '5.4%', inf_score: 54, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Himalayan Valley Air', wind: '10 knots', rain: '16 mm/hr' },
    'Netherlands': { iso: 'NL', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$1.12 Trillion', gdp_growth: '1.2%', gdp_num: 1.2, inf_rate: '2.6%', inf_score: 26, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Rotterdam North Sea Transit', wind: '16 knots', rain: '15 mm/hr' },
    'New Zealand': { iso: 'NZ', currency: 'NZD - New Zealand Dollar', region: 'Oceania', gdp_val: '$253.5 Billion', gdp_growth: '1.8%', gdp_num: 1.8, inf_rate: '3.3%', inf_score: 33, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Tasman Sea Coastal Breezes', wind: '18 knots', rain: '20 mm/hr' },
    'Nicaragua': { iso: 'NI', currency: 'NIO - Nicaraguan Cordoba', region: 'Americas', gdp_val: '$17.8 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '5.2%', inf_score: 52, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Pacific & Caribbean Coastal', wind: '14 knots', rain: '22 mm/hr' },
    'Niger': { iso: 'NE', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$16.8 Billion', gdp_growth: '5.4%', gdp_num: 5.4, inf_rate: '4.8%', inf_score: 48, risk_score: 58, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Saharan Dry Winds', wind: '15 knots', rain: '4 mm/hr' },
    'Nigeria': { iso: 'NG', currency: 'NGN - Nigerian Naira', region: 'Africa', gdp_val: '$374.9 Billion', gdp_growth: '3.3%', gdp_num: 3.3, inf_rate: '28.9%', inf_score: 85, risk_score: 54, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Guinea Maritime', wind: '14 knots', rain: '26 mm/hr' },
    'North Korea': { iso: 'KP', currency: 'KPW - North Korean Won', region: 'Asia', gdp_val: '$16.3 Billion', gdp_growth: '0.5%', gdp_num: 0.5, inf_rate: '12.0%', inf_score: 70, risk_score: 82, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Sea of Japan Coastal', wind: '15 knots', rain: '16 mm/hr' },
    'North Macedonia': { iso: 'MK', currency: 'MKD - Macedonian Denar', region: 'Europe', gdp_val: '$14.8 Billion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '3.2%', inf_score: 32, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Balkan Valley Clear', wind: '12 knots', rain: '12 mm/hr' },
    'Norway': { iso: 'NO', currency: 'NOK - Norwegian Krone', region: 'Europe', gdp_val: '$485.5 Billion', gdp_growth: '1.6%', gdp_num: 1.6, inf_rate: '3.0%', inf_score: 30, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'North Sea & Fjord Coastal', wind: '18 knots', rain: '18 mm/hr' },
    'Oman': { iso: 'OM', currency: 'OMR - Omani Rial', region: 'Asia', gdp_val: '$108.3 Billion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '1.2%', inf_score: 12, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Arabian Sea Coastal Calm', wind: '14 knots', rain: '3 mm/hr' },
    'Pakistan': { iso: 'PK', currency: 'PKR - Pakistani Rupee', region: 'Asia', gdp_val: '$340.6 Billion', gdp_growth: '2.0%', gdp_num: 2.0, inf_rate: '20.5%', inf_score: 75, risk_score: 58, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Arabian Sea Coastal', wind: '14 knots', rain: '12 mm/hr' },
    'Palau': { iso: 'PW', currency: 'USD - US Dollar', region: 'Oceania', gdp_val: '$0.32 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '2.4%', inf_score: 24, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Western Pacific Trade Winds', wind: '15 knots', rain: '24 mm/hr' },
    'Panama': { iso: 'PA', currency: 'PAB / USD - Balboa / US Dollar', region: 'Americas', gdp_val: '$83.4 Billion', gdp_growth: '4.5%', gdp_num: 4.5, inf_rate: '1.5%', inf_score: 15, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Panama Canal Transit Calm', wind: '13 knots', rain: '24 mm/hr' },
    'Papua New Guinea': { iso: 'PG', currency: 'PGK - Kina', region: 'Oceania', gdp_val: '$31.7 Billion', gdp_growth: '3.4%', gdp_num: 3.4, inf_rate: '4.5%', inf_score: 45, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Coral Sea & Pacific Coastal', wind: '15 knots', rain: '28 mm/hr' },
    'Paraguay': { iso: 'PY', currency: 'PYG - Guarani', region: 'Americas', gdp_val: '$43.0 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '3.5%', inf_score: 35, risk_score: 34, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Parana Basin River Ports', wind: '12 knots', rain: '16 mm/hr' },
    'Peru': { iso: 'PE', currency: 'PEN - Sol', region: 'Americas', gdp_val: '$267.6 Billion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '2.8%', inf_score: 28, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Pacific Coastal Breezes', wind: '15 knots', rain: '12 mm/hr' },
    'Philippines': { iso: 'PH', currency: 'PHP - Philippine Peso', region: 'Asia', gdp_val: '$437.1 Billion', gdp_growth: '5.9%', gdp_num: 5.9, inf_rate: '3.4%', inf_score: 34, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'South China Sea Monsoon Watch', wind: '16 knots', rain: '28 mm/hr' },
    'Poland': { iso: 'PL', currency: 'PLN - Polish Zloty', region: 'Europe', gdp_val: '$811.2 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '3.6%', inf_score: 36, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic Sea Coastal Air', wind: '15 knots', rain: '14 mm/hr' },
    'Portugal': { iso: 'PT', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$287.4 Billion', gdp_growth: '2.0%', gdp_num: 2.0, inf_rate: '2.3%', inf_score: 23, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Atlantic Coastal Breeze', wind: '16 knots', rain: '14 mm/hr' },
    'Qatar': { iso: 'QA', currency: 'QAR - Qatari Riyal', region: 'Asia', gdp_val: '$235.5 Billion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '1.8%', inf_score: 18, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Persian Gulf Calm Waters', wind: '13 knots', rain: '2 mm/hr' },
    'Romania': { iso: 'RO', currency: 'RON - Romanian Leu', region: 'Europe', gdp_val: '$350.4 Billion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '5.2%', inf_score: 52, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Black Sea Coastal Air', wind: '14 knots', rain: '13 mm/hr' },
    'Russia': { iso: 'RU', currency: 'RUB - Russian Ruble', region: 'Europe/Asia', gdp_val: '$2.02 Trillion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '7.8%', inf_score: 68, risk_score: 65, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Baltic & Arctic Coastal Air', wind: '16 knots', rain: '14 mm/hr' },
    'Rwanda': { iso: 'RW', currency: 'RWF - Rwandan Franc', region: 'Africa', gdp_val: '$14.1 Billion', gdp_growth: '6.8%', gdp_num: 6.8, inf_rate: '4.5%', inf_score: 45, risk_score: 34, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'East African Highland Clear', wind: '11 knots', rain: '16 mm/hr' },
    'Saint Kitts and Nevis': { iso: 'KN', currency: 'XCD - East Caribbean Dollar', region: 'Americas', gdp_val: '$1.1 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '2.4%', inf_score: 24, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Island Trade Winds', wind: '16 knots', rain: '16 mm/hr' },
    'Saint Lucia': { iso: 'LC', currency: 'XCD - East Caribbean Dollar', region: 'Americas', gdp_val: '$2.5 Billion', gdp_growth: '3.6%', gdp_num: 3.6, inf_rate: '2.6%', inf_score: 26, risk_score: 26, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Island Trade Winds', wind: '16 knots', rain: '16 mm/hr' },
    'Saint Vincent and the Grenadines': { iso: 'VC', currency: 'XCD - East Caribbean Dollar', region: 'Americas', gdp_val: '$1.0 Billion', gdp_growth: '4.2%', gdp_num: 4.2, inf_rate: '2.8%', inf_score: 28, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Trade Winds', wind: '16 knots', rain: '18 mm/hr' },
    'Samoa': { iso: 'WS', currency: 'WST - Samoan Tala', region: 'Oceania', gdp_val: '$0.93 Billion', gdp_growth: '3.4%', gdp_num: 3.4, inf_rate: '3.2%', inf_score: 32, risk_score: 30, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'South Pacific Trade Winds', wind: '16 knots', rain: '24 mm/hr' },
    'San Marino': { iso: 'SM', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$1.8 Billion', gdp_growth: '1.4%', gdp_num: 1.4, inf_rate: '2.1%', inf_score: 21, risk_score: 16, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Apennine Mountain Clear', wind: '10 knots', rain: '12 mm/hr' },
    'Sao Tome and Principe': { iso: 'ST', currency: 'STN - Dobra', region: 'Africa', gdp_val: '$0.61 Billion', gdp_growth: '2.9%', gdp_num: 2.9, inf_rate: '6.5%', inf_score: 55, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Gulf of Guinea Tropical Air', wind: '13 knots', rain: '25 mm/hr' },
    'Saudi Arabia': { iso: 'SA', currency: 'SAR - Saudi Riyal', region: 'Asia', gdp_val: '$1.11 Trillion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '1.6%', inf_score: 16, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Red Sea & Persian Gulf Calm', wind: '14 knots', rain: '2 mm/hr' },
    'Senegal': { iso: 'SN', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$31.0 Billion', gdp_growth: '7.1%', gdp_num: 7.1, inf_rate: '2.8%', inf_score: 28, risk_score: 36, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Atlantic Coastal Trade Winds', wind: '15 knots', rain: '14 mm/hr' },
    'Serbia': { iso: 'RS', currency: 'RSD - Serbian Dinar', region: 'Europe', gdp_val: '$75.2 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '4.5%', inf_score: 45, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Balkan Continental Clear', wind: '12 knots', rain: '13 mm/hr' },
    'Seychelles': { iso: 'SC', currency: 'SCR - Seychellois Rupee', region: 'Africa', gdp_val: '$2.1 Billion', gdp_growth: '3.8%', gdp_num: 3.8, inf_rate: '2.2%', inf_score: 22, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Indian Ocean Island Calm', wind: '15 knots', rain: '20 mm/hr' },
    'Sierra Leone': { iso: 'SL', currency: 'SLE - Sierra Leonean Leone', region: 'Africa', gdp_val: '$4.1 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '28.4%', inf_score: 82, risk_score: 56, risk_lvl: 'Medium-High Risk', badge: 'warning', weather_cond: 'Atlantic Coastal Monsoon', wind: '14 knots', rain: '28 mm/hr' },
    'Singapore': { iso: 'SG', currency: 'SGD - Singapore Dollar', region: 'Asia', gdp_val: '$501.4 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '2.4%', inf_score: 24, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Strait of Singapore Calm Waters', wind: '12 knots', rain: '24 mm/hr' },
    'Slovakia': { iso: 'SK', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$132.8 Billion', gdp_growth: '2.0%', gdp_num: 2.0, inf_rate: '3.1%', inf_score: 31, risk_score: 21, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Central European Clear', wind: '12 knots', rain: '13 mm/hr' },
    'Slovenia': { iso: 'SI', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$68.2 Billion', gdp_growth: '2.2%', gdp_num: 2.2, inf_rate: '2.6%', inf_score: 26, risk_score: 19, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Adriatic & Alpine Calm', wind: '12 knots', rain: '14 mm/hr' },
    'Solomon Islands': { iso: 'SB', currency: 'SBD - Solomon Islands Dollar', region: 'Oceania', gdp_val: '$1.6 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '4.2%', inf_score: 42, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'South Pacific Trade Winds', wind: '16 knots', rain: '26 mm/hr' },
    'Somalia': { iso: 'SO', currency: 'SOS - Somali Shilling', region: 'Africa', gdp_val: '$11.6 Billion', gdp_growth: '3.7%', gdp_num: 3.7, inf_rate: '6.1%', inf_score: 61, risk_score: 72, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Gulf of Aden Maritime Watch', wind: '17 knots', rain: '6 mm/hr' },
    'South Africa': { iso: 'ZA', currency: 'ZAR - South African Rand', region: 'Africa', gdp_val: '$377.8 Billion', gdp_growth: '1.2%', gdp_num: 1.2, inf_rate: '5.1%', inf_score: 51, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Cape of Good Hope Transit', wind: '18 knots', rain: '14 mm/hr' },
    'South Korea': { iso: 'KR', currency: 'KRW - South Korean Won', region: 'Asia', gdp_val: '$1.71 Trillion', gdp_growth: '2.3%', gdp_num: 2.3, inf_rate: '2.6%', inf_score: 26, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Yellow & East Sea Calm', wind: '15 knots', rain: '16 mm/hr' },
    'South Sudan': { iso: 'SS', currency: 'SSP - South Sudanese Pound', region: 'Africa', gdp_val: '$6.5 Billion', gdp_growth: '1.5%', gdp_num: 1.5, inf_rate: '35.0%', inf_score: 88, risk_score: 76, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Inland Nile Basin Clear', wind: '12 knots', rain: '16 mm/hr' },
    'Spain': { iso: 'ES', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$1.58 Trillion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '2.8%', inf_score: 28, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Mediterranean & Atlantic Calm', wind: '15 knots', rain: '12 mm/hr' },
    'Sri Lanka': { iso: 'LK', currency: 'LKR - Sri Lankan Rupee', region: 'Asia', gdp_val: '$84.4 Billion', gdp_growth: '2.2%', gdp_num: 2.2, inf_rate: '5.8%', inf_score: 58, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Indian Ocean Monsoon Breezes', wind: '16 knots', rain: '24 mm/hr' },
    'Sudan': { iso: 'SD', currency: 'SDG - Sudanese Pound', region: 'Africa', gdp_val: '$26.0 Billion', gdp_growth: '-2.0%', gdp_num: -2.0, inf_rate: '65.0%', inf_score: 92, risk_score: 78, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Red Sea Coastal & Saharan Air', wind: '16 knots', rain: '4 mm/hr' },
    'Suriname': { iso: 'SR', currency: 'SRD - Surinamese Dollar', region: 'Americas', gdp_val: '$3.8 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '14.2%', inf_score: 68, risk_score: 46, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Atlantic Coastal Trade Winds', wind: '14 knots', rain: '24 mm/hr' },
    'Sweden': { iso: 'SE', currency: 'SEK - Swedish Krona', region: 'Europe', gdp_val: '$593.3 Billion', gdp_growth: '1.1%', gdp_num: 1.1, inf_rate: '2.2%', inf_score: 22, risk_score: 18, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Baltic Sea Fjord Coastal', wind: '16 knots', rain: '14 mm/hr' },
    'Switzerland': { iso: 'CH', currency: 'CHF - Swiss Franc', region: 'Europe', gdp_val: '$905.7 Billion', gdp_growth: '1.3%', gdp_num: 1.3, inf_rate: '1.4%', inf_score: 14, risk_score: 16, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Alpine Clear & Calm', wind: '10 knots', rain: '13 mm/hr' },
    'Syria': { iso: 'SY', currency: 'SYP - Syrian Pound', region: 'Asia', gdp_val: '$12.0 Billion', gdp_growth: '0.8%', gdp_num: 0.8, inf_rate: '45.0%', inf_score: 86, risk_score: 78, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Eastern Mediterranean Coastal', wind: '14 knots', rain: '6 mm/hr' },
    'Taiwan': { iso: 'TW', currency: 'TWD - New Taiwan Dollar', region: 'Asia', gdp_val: '$790.7 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '2.1%', inf_score: 21, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Taiwan Strait Coastal Breeze', wind: '16 knots', rain: '22 mm/hr' },
    'Tajikistan': { iso: 'TJ', currency: 'TJS - Tajikistani Somoni', region: 'Asia', gdp_val: '$12.1 Billion', gdp_growth: '6.5%', gdp_num: 6.5, inf_rate: '3.8%', inf_score: 38, risk_score: 40, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Highland Continental Air', wind: '11 knots', rain: '10 mm/hr' },
    'Tanzania': { iso: 'TZ', currency: 'TZS - Tanzanian Shilling', region: 'Africa', gdp_val: '$84.0 Billion', gdp_growth: '5.3%', gdp_num: 5.3, inf_rate: '3.1%', inf_score: 31, risk_score: 36, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Indian Ocean Coastal Breezes', wind: '15 knots', rain: '18 mm/hr' },
    'Thailand': { iso: 'TH', currency: 'THB - Thai Baht', region: 'Asia', gdp_val: '$514.9 Billion', gdp_growth: '2.8%', gdp_num: 2.8, inf_rate: '1.2%', inf_score: 12, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Gulf of Thailand & Andaman Port', wind: '13 knots', rain: '22 mm/hr' },
    'Timor-Leste': { iso: 'TL', currency: 'USD - US Dollar', region: 'Asia', gdp_val: '$2.0 Billion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '4.1%', inf_score: 41, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Timor Sea Tropical Breeze', wind: '14 knots', rain: '20 mm/hr' },
    'Togo': { iso: 'TG', currency: 'XOF - West African CFA Franc', region: 'Africa', gdp_val: '$9.1 Billion', gdp_growth: '5.3%', gdp_num: 5.3, inf_rate: '3.4%', inf_score: 34, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Gulf of Guinea Coastal Air', wind: '13 knots', rain: '20 mm/hr' },
    'Tonga': { iso: 'TO', currency: 'TOP - Tongan Paanga', region: 'Oceania', gdp_val: '$0.54 Billion', gdp_growth: '2.6%', gdp_num: 2.6, inf_rate: '3.8%', inf_score: 38, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'South Pacific Trade Winds', wind: '17 knots', rain: '24 mm/hr' },
    'Trinidad and Tobago': { iso: 'TT', currency: 'TTD - Trinidad & Tobago Dollar', region: 'Americas', gdp_val: '$28.1 Billion', gdp_growth: '2.4%', gdp_num: 2.4, inf_rate: '2.8%', inf_score: 28, risk_score: 28, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Caribbean Island Coastal Air', wind: '16 knots', rain: '18 mm/hr' },
    'Tunisia': { iso: 'TN', currency: 'TND - Tunisian Dinar', region: 'Africa', gdp_val: '$48.5 Billion', gdp_growth: '1.9%', gdp_num: 1.9, inf_rate: '7.2%', inf_score: 52, risk_score: 42, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Mediterranean Coastal Breezes', wind: '15 knots', rain: '10 mm/hr' },
    'Turkey': { iso: 'TR', currency: 'TRY - Turkish Lira', region: 'Asia/Europe', gdp_val: '$1.11 Trillion', gdp_growth: '3.1%', gdp_num: 3.1, inf_rate: '45.0%', inf_score: 85, risk_score: 46, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Bosphorus & Mediterranean Calm', wind: '16 knots', rain: '12 mm/hr' },
    'Turkmenistan': { iso: 'TM', currency: 'TMT - Turkmenistani Manat', region: 'Asia', gdp_val: '$59.8 Billion', gdp_growth: '6.3%', gdp_num: 6.3, inf_rate: '5.2%', inf_score: 52, risk_score: 44, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Caspian Sea Coastal Air', wind: '14 knots', rain: '6 mm/hr' },
    'Tuvalu': { iso: 'TV', currency: 'AUD - Australian Dollar', region: 'Oceania', gdp_val: '$0.06 Billion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '3.1%', inf_score: 31, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Equatorial Trade Winds', wind: '16 knots', rain: '26 mm/hr' },
    'Uganda': { iso: 'UG', currency: 'UGX - Ugandan Shilling', region: 'Africa', gdp_val: '$49.3 Billion', gdp_growth: '5.8%', gdp_num: 5.8, inf_rate: '3.2%', inf_score: 32, risk_score: 38, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'Lake Victoria Continental', wind: '12 knots', rain: '20 mm/hr' },
    'Ukraine': { iso: 'UA', currency: 'UAH - Ukrainian Hryvnia', region: 'Europe', gdp_val: '$178.8 Billion', gdp_growth: '3.2%', gdp_num: 3.2, inf_rate: '5.1%', inf_score: 51, risk_score: 72, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Black Sea Coastal Air', wind: '15 knots', rain: '14 mm/hr' },
    'United Arab Emirates': { iso: 'AE', currency: 'AED - UAE Dirham', region: 'Asia', gdp_val: '$504.2 Billion', gdp_growth: '3.9%', gdp_num: 3.9, inf_rate: '1.6%', inf_score: 16, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Persian Gulf Calm Waters', wind: '13 knots', rain: '2 mm/hr' },
    'United Kingdom': { iso: 'GB', currency: 'GBP - British Pound Sterling', region: 'Europe', gdp_val: '$3.34 Trillion', gdp_growth: '1.1%', gdp_num: 1.1, inf_rate: '2.3%', inf_score: 23, risk_score: 20, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'North Sea & Channel Transit', wind: '17 knots', rain: '18 mm/hr' },
    'United States': { iso: 'US', currency: 'USD - US Dollar', region: 'Americas', gdp_val: '$27.36 Trillion', gdp_growth: '2.5%', gdp_num: 2.5, inf_rate: '2.6%', inf_score: 26, risk_score: 22, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Atlantic & Pacific Coastal Clear', wind: '15 knots', rain: '16 mm/hr' },
    'Uruguay': { iso: 'UY', currency: 'UYU - Uruguayan Peso', region: 'Americas', gdp_val: '$77.2 Billion', gdp_growth: '3.2%', gdp_num: 3.2, inf_rate: '4.5%', inf_score: 45, risk_score: 24, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'South Atlantic River Ports', wind: '16 knots', rain: '16 mm/hr' },
    'Uzbekistan': { iso: 'UZ', currency: 'UZS - Uzbekistani Som', region: 'Asia', gdp_val: '$90.9 Billion', gdp_growth: '5.6%', gdp_num: 5.6, inf_rate: '8.1%', inf_score: 58, risk_score: 40, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Continental Desert Clear', wind: '13 knots', rain: '8 mm/hr' },
    'Vanuatu': { iso: 'VU', currency: 'VUV - Vanuatu Vatu', region: 'Oceania', gdp_val: '$1.1 Billion', gdp_growth: '2.9%', gdp_num: 2.9, inf_rate: '3.6%', inf_score: 36, risk_score: 34, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'South Pacific Trade Winds', wind: '17 knots', rain: '26 mm/hr' },
    'Vatican City': { iso: 'VA', currency: 'EUR - Euro', region: 'Europe', gdp_val: '$0.05 Billion', gdp_growth: '1.0%', gdp_num: 1.0, inf_rate: '1.8%', inf_score: 18, risk_score: 15, risk_lvl: 'Low Risk', badge: 'success', weather_cond: 'Mediterranean Inland Calm', wind: '9 knots', rain: '12 mm/hr' },
    'Venezuela': { iso: 'VE', currency: 'VES - Venezuelan Bolivar', region: 'Americas', gdp_val: '$92.3 Billion', gdp_growth: '4.0%', gdp_num: 4.0, inf_rate: '59.0%', inf_score: 90, risk_score: 72, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Caribbean Coastal Air', wind: '14 knots', rain: '20 mm/hr' },
    'Vietnam': { iso: 'VN', currency: 'VND - Vietnamese Dong', region: 'Asia', gdp_val: '$429.7 Billion', gdp_growth: '6.0%', gdp_num: 6.0, inf_rate: '3.2%', inf_score: 32, risk_score: 32, risk_lvl: 'Low-Medium Risk', badge: 'info', weather_cond: 'South China Sea Coastal Breezes', wind: '15 knots', rain: '28 mm/hr' },
    'Yemen': { iso: 'YE', currency: 'YER - Yemeni Rial', region: 'Asia', gdp_val: '$21.6 Billion', gdp_growth: '-1.0%', gdp_num: -1.0, inf_rate: '24.0%', inf_score: 78, risk_score: 84, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Gulf of Aden & Red Sea Watch', wind: '17 knots', rain: '4 mm/hr' },
    'Zambia': { iso: 'ZM', currency: 'ZMW - Zambian Kwacha', region: 'Africa', gdp_val: '$28.1 Billion', gdp_growth: '4.3%', gdp_num: 4.3, inf_rate: '13.2%', inf_score: 64, risk_score: 48, risk_lvl: 'Medium Risk', badge: 'warning', weather_cond: 'Highland Plateau Air', wind: '12 knots', rain: '16 mm/hr' },
    'Zimbabwe': { iso: 'ZW', currency: 'ZWL - Zimbabwean Dollar', region: 'Africa', gdp_val: '$26.5 Billion', gdp_growth: '3.5%', gdp_num: 3.5, inf_rate: '48.0%', inf_score: 86, risk_score: 64, risk_lvl: 'High Risk', badge: 'danger', weather_cond: 'Highland Savanna Breezes', wind: '13 knots', rain: '14 mm/hr' }
};

document.addEventListener('DOMContentLoaded', () => {
    if (!window.INITIAL_DSS_DATA) {
        console.warn('INITIAL_DSS_DATA not found. Skipping Decision Support initializations.');
        return;
    }

    let dssData = window.INITIAL_DSS_DATA;
    let autoRefreshInterval = null;

    // Initialize Chart.js defaults if needed
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '#e0e0e0';
        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    }

    // 1. Initialize Country Comparison Engine with 195 Countries
    initComparisonEngine(dssData);

    // 2. Start 60-second AJAX Auto-Refresh
    startAutoRefresh();

    function initComparisonEngine(data) {
        const searchA = document.getElementById('searchCountryA');
        const searchB = document.getElementById('searchCountryB');
        const dropdownA = document.getElementById('dropdownCountryA');
        const dropdownB = document.getElementById('dropdownCountryB');
        const selectA = document.getElementById('selectCountryA');
        const selectB = document.getElementById('selectCountryB');
        const btnSwap = document.getElementById('btnSwapCountries');
        const btnReset = document.getElementById('btnResetComparison');

        if (!searchA || !searchB || !selectA || !selectB) return;

        const allCountryNames = Object.keys(GLOBAL_195_COUNTRIES).sort();

        // Setup Dropdown for Country A
        const setupDropdown = (inputEl, dropdownEl, hiddenEl, otherHiddenEl) => {
            const renderList = (query = '') => {
                dropdownEl.innerHTML = '';
                const qClean = query.trim().toLowerCase();

                const filtered = allCountryNames.filter(name => {
                    if (!qClean) return true;
                    const meta = GLOBAL_195_COUNTRIES[name];
                    return name.toLowerCase().includes(qClean) || meta.iso.toLowerCase().includes(qClean) || meta.currency.toLowerCase().includes(qClean);
                });

                if (filtered.length === 0) {
                    dropdownEl.innerHTML = `<div class="p-2 text-muted text-center" style="font-size: 11.5px;">Negara tidak ditemukan</div>`;
                } else {
                    filtered.slice(0, 80).forEach(name => {
                        const meta = GLOBAL_195_COUNTRIES[name];
                        const item = document.createElement('div');
                        item.className = 'dropdown-item d-flex justify-content-between align-items-center py-1 px-2 text-white border-bottom border-secondary';
                        item.style.cursor = 'pointer';
                        item.style.fontSize = '12px';
                        item.innerHTML = `
                            <div>
                                <span class="fw-bold me-1">${name}</span>
                                <span class="badge bg-dark border border-secondary text-info" style="font-size: 10px;">${meta.iso}</span>
                            </div>
                            <span class="text-secondary" style="font-size: 11px;">${meta.region}</span>
                        `;
                        item.addEventListener('click', () => {
                            inputEl.value = `${name} (${meta.iso})`;
                            hiddenEl.value = name;
                            dropdownEl.style.display = 'none';
                            renderComparisonEngine(dssData || data, selectA.value, selectB.value);
                        });
                        item.addEventListener('mouseenter', () => item.style.background = 'rgba(56, 189, 248, 0.2)');
                        item.addEventListener('mouseleave', () => item.style.background = 'transparent');
                        dropdownEl.appendChild(item);
                    });
                }
                dropdownEl.style.display = 'block';
            };

            inputEl.addEventListener('input', (e) => renderList(e.target.value));
            inputEl.addEventListener('focus', () => renderList(inputEl.value.replace(/\s*\(.*\)/, '')));
        };

        setupDropdown(searchA, dropdownA, selectA, selectB);
        setupDropdown(searchB, dropdownB, selectB, selectA);

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (dropdownA && !searchA.contains(e.target) && !dropdownA.contains(e.target)) dropdownA.style.display = 'none';
            if (dropdownB && !searchB.contains(e.target) && !dropdownB.contains(e.target)) dropdownB.style.display = 'none';
        });

        // Set default values (empty state initially or check defaults from URL parameter)
        const urlParams = new URLSearchParams(window.location.search);
        const urlCountry = urlParams.get('country');
        let initialA = '';
        if (urlCountry && urlCountry !== 'reset' && urlCountry !== '-' && GLOBAL_195_COUNTRIES[urlCountry]) {
            initialA = urlCountry;
            selectA.value = initialA;
            searchA.value = `${initialA} (${GLOBAL_195_COUNTRIES[initialA].iso})`;
            const featKey = typeof window.getFeatureStorageKey === 'function' ? window.getFeatureStorageKey() : 'selected_country_decision_support';
            sessionStorage.setItem(featKey, initialA);
            localStorage.setItem(featKey, initialA);
        } else {
            selectA.value = '';
            searchA.value = '';
        }
        selectB.value = '';
        searchB.value = '';

        renderComparisonEngine(dssData || data, initialA, '');

        // Swap button
        if (btnSwap) {
            btnSwap.addEventListener('click', () => {
                const tempHidden = selectA.value;
                const tempText = searchA.value;
                selectA.value = selectB.value;
                searchA.value = searchB.value;
                selectB.value = tempHidden;
                searchB.value = tempText;
                renderComparisonEngine(dssData || data, selectA.value, selectB.value);
            });
        }

        // Reset button
        if (btnReset) {
            btnReset.addEventListener('click', () => {
                selectA.value = '';
                selectB.value = '';
                searchA.value = '';
                searchB.value = '';
                renderComparisonEngine(dssData || data, '', '');
            });
        }
    }

    /**
     * Render 1 Master Table comparing Country A vs Country B (Compact Mode without Scrolling)
     */
    function renderComparisonEngine(data, nameA, nameB) {
        const getCountryObj = (countryName, fallbackLabel) => {
            if (!countryName) return {
                name: fallbackLabel,
                iso: '--',
                flag: '❓',
                gdp: { value: '--', growth: '--', growth_num: 0, score: 0, status: 'Belum dipilih' },
                inflation: { rate: '--', score: 0, status: 'Belum dipilih' },
                risk: { score: 0, level: 'Belum dipilih', badge_color: 'secondary', maritime: '--', financial: '--' },
                weather: { score: 0, condition: 'Belum dipilih', wind_speed: '--', rainfall: '--' },
                currency: { code: '--', score: 0, volatility: '--', trend: '--' }
            };

            // Check baseline in GLOBAL_195_COUNTRIES
            const meta = GLOBAL_195_COUNTRIES[countryName] || {
                iso: 'UN', currency: 'USD - Universal Currency', region: 'Global',
                gdp_val: '$150.0 Billion', gdp_growth: '2.5%', gdp_num: 2.5,
                inf_rate: '3.2%', inf_score: 32, risk_score: 35, risk_lvl: 'Low-Medium Risk', badge: 'info',
                weather_cond: 'Clear Coastal Waters', wind: '14 knots', rain: '14 mm/hr'
            };

            // Prioritize live API metrics from data.comparison_engine.countries (or data.countries) if available
            let apiObj = null;
            const currentData = dssData || data;
            if (currentData && currentData.comparison_engine && currentData.comparison_engine.countries) {
                const apiCountries = currentData.comparison_engine.countries;
                apiObj = apiCountries[meta.iso] || 
                         Object.values(apiCountries).find(c => (c.name && c.name.toLowerCase() === countryName.toLowerCase()) || (c.iso && c.iso.toUpperCase() === meta.iso.toUpperCase()));
            }
            if (!apiObj && currentData && currentData.countries) {
                apiObj = currentData.countries[meta.iso] || 
                         Object.values(currentData.countries).find(c => (c.name && c.name.toLowerCase() === countryName.toLowerCase()) || (c.iso && c.iso.toUpperCase() === meta.iso.toUpperCase()));
            }

            if (apiObj) {
                return {
                    name: apiObj.name || countryName,
                    iso: apiObj.iso || meta.iso,
                    flag: apiObj.flag || getFlagEmoji(meta.iso),
                    gdp: {
                        value: (apiObj.gdp && apiObj.gdp.value) ? apiObj.gdp.value : meta.gdp_val,
                        growth: (apiObj.gdp && apiObj.gdp.growth) ? apiObj.gdp.growth : meta.gdp_growth,
                        growth_num: (apiObj.gdp && apiObj.gdp.growth_num !== undefined) ? apiObj.gdp.growth_num : meta.gdp_num,
                        score: (apiObj.gdp && apiObj.gdp.score !== undefined) ? apiObj.gdp.score : Math.min(98, Math.max(25, Math.round(meta.gdp_num * 18 + 30))),
                        status: (apiObj.gdp && apiObj.gdp.status) ? apiObj.gdp.status : `${meta.region} Regional Economy`
                    },
                    inflation: {
                        rate: (apiObj.inflation && apiObj.inflation.rate) ? apiObj.inflation.rate : meta.inf_rate,
                        score: (apiObj.inflation && apiObj.inflation.score !== undefined) ? apiObj.inflation.score : meta.inf_score,
                        status: (apiObj.inflation && apiObj.inflation.status) ? apiObj.inflation.status : (meta.inf_score < 30 ? 'Price Stability Target' : (meta.inf_score < 60 ? 'Moderate Inflationary Pressure' : 'High Monetary Volatility'))
                    },
                    risk: {
                        score: (apiObj.risk && apiObj.risk.score !== undefined) ? apiObj.risk.score : meta.risk_score,
                        level: (apiObj.risk && apiObj.risk.level) ? apiObj.risk.level : meta.risk_lvl,
                        badge_color: (apiObj.risk && apiObj.risk.badge_color) ? apiObj.risk.badge_color : meta.badge,
                        maritime: (apiObj.risk && apiObj.risk.maritime !== undefined) ? apiObj.risk.maritime : Math.round(meta.risk_score * 0.45 + 10),
                        financial: (apiObj.risk && apiObj.risk.financial !== undefined) ? apiObj.risk.financial : Math.round(meta.risk_score * 0.55 + 5)
                    },
                    weather: {
                        score: (apiObj.weather && apiObj.weather.score !== undefined) ? apiObj.weather.score : Math.min(95, Math.max(15, Math.round((parseInt(meta.wind) || 14) * 2.2))),
                        condition: (apiObj.weather && apiObj.weather.condition) ? apiObj.weather.condition : meta.weather_cond,
                        wind_speed: (apiObj.weather && apiObj.weather.wind_speed) ? apiObj.weather.wind_speed : meta.wind,
                        rainfall: (apiObj.weather && apiObj.weather.rainfall) ? apiObj.weather.rainfall : meta.rain
                    },
                    currency: {
                        code: (apiObj.currency && apiObj.currency.code) ? apiObj.currency.code : meta.currency,
                        score: (apiObj.currency && apiObj.currency.score !== undefined) ? apiObj.currency.score : Math.min(95, Math.max(15, Math.round(meta.inf_score * 0.7 + 12))),
                        volatility: (apiObj.currency && apiObj.currency.volatility) ? apiObj.currency.volatility : `FX Volatility Index: ${(meta.inf_score * 0.25 + 5).toFixed(1)}`,
                        trend: (apiObj.currency && apiObj.currency.trend) ? apiObj.currency.trend : (meta.inf_score < 35 ? 'Stable FX' : (meta.inf_score < 65 ? 'Moderate Fluctuation' : 'High Volatility Risk'))
                    }
                };
            }

            return {
                name: countryName,
                iso: meta.iso,
                flag: getFlagEmoji(meta.iso),
                gdp: {
                    value: meta.gdp_val,
                    growth: meta.gdp_growth,
                    growth_num: meta.gdp_num,
                    score: Math.min(98, Math.max(25, Math.round(meta.gdp_num * 18 + 30))),
                    status: `${meta.region} Regional Economy`
                },
                inflation: {
                    rate: meta.inf_rate,
                    score: meta.inf_score,
                    status: meta.inf_score < 30 ? 'Price Stability Target' : (meta.inf_score < 60 ? 'Moderate Inflationary Pressure' : 'High Monetary Volatility')
                },
                risk: {
                    score: meta.risk_score,
                    level: meta.risk_lvl,
                    badge_color: meta.badge,
                    maritime: Math.round(meta.risk_score * 0.45 + 10),
                    financial: Math.round(meta.risk_score * 0.55 + 5)
                },
                weather: {
                    score: Math.min(95, Math.max(15, Math.round((parseInt(meta.wind) || 14) * 2.2))),
                    condition: meta.weather_cond,
                    wind_speed: meta.wind,
                    rainfall: meta.rain
                },
                currency: {
                    code: meta.currency,
                    score: Math.min(95, Math.max(15, Math.round(meta.inf_score * 0.7 + 12))),
                    volatility: `FX Volatility Index: ${(meta.inf_score * 0.25 + 5).toFixed(1)}`,
                    trend: meta.inf_score < 35 ? 'Stable FX' : (meta.inf_score < 65 ? 'Moderate Fluctuation' : 'High Volatility Risk')
                }
            };
        };

        const cA = getCountryObj(nameA, 'Pilih Negara 1');
        const cB = getCountryObj(nameB, 'Pilih Negara 2');

        const elTitle = document.getElementById('comparisonTitleText');
        const thA = document.getElementById('thCountryA');
        const thB = document.getElementById('thCountryB');

        if (!nameA && !nameB) {
            if (elTitle) elTitle.textContent = 'Belum Ada Negara Dipilih (Kosong)';
            if (thA) thA.textContent = 'PILIH NEGARA 1 ❓';
            if (thB) thB.textContent = 'PILIH NEGARA 2 ❓';
        } else {
            const titleStr = `${cA.name || nameA || '?'} (${cA.iso}) vs ${cB.name || nameB || '?'} (${cB.iso})`;
            if (elTitle) elTitle.textContent = titleStr;
            if (thA) thA.textContent = `${cA.name.toUpperCase()} ${cA.flag}`;
            if (thB) thB.textContent = `${cB.name.toUpperCase()} ${cB.flag}`;
        }

        const tbody = document.getElementById('comparisonMainTableBody');
        if (!tbody) return;

        if (!nameA && !nameB) {
            tbody.innerHTML = `
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="py-3 px-3 border-start border-primary border-4">
                        <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">GDP &amp; Pertumbuhan</div>
                        <div class="small" style="color:#3b82f6;">Economic Scale</div>
                    </td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-end py-3 px-3"><span class="small fw-semibold" style="color:#94a3b8;">Pilih 2 Negara</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="py-3 px-3 border-start border-danger border-4">
                        <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Inflasi &amp; Stabilitas Harga</div>
                        <div class="small" style="color:#ef4444;">Shock Index</div>
                    </td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-end py-3 px-3"><span class="small fw-semibold" style="color:#94a3b8;">Pilih 2 Negara</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="py-3 px-3 border-start border-warning border-4">
                        <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Risiko Rantai Pasok</div>
                        <div class="small" style="color:#d97706;">Disruption Index</div>
                    </td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-end py-3 px-3"><span class="small fw-semibold" style="color:#94a3b8;">Pilih 2 Negara</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="py-3 px-3 border-start border-info border-4">
                        <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Cuaca &amp; Pelabuhan</div>
                        <div class="small" style="color:#0ea5e9;">Maritime Transit</div>
                    </td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-end py-3 px-3"><span class="small fw-semibold" style="color:#94a3b8;">Pilih 2 Negara</span></td>
                </tr>
                <tr>
                    <td class="py-3 px-3 border-start border-success border-4">
                        <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Mata Uang &amp; Valas</div>
                        <div class="small" style="color:#10b981;">FX Volatility</div>
                    </td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-center py-3 px-3"><div class="fw-bold" style="font-size: 20px; color:#94a3b8;">--</div><div class="small" style="color:#94a3b8;">Belum dipilih</div></td>
                    <td class="text-end py-3 px-3"><span class="small fw-semibold" style="color:#94a3b8;">Pilih 2 Negara</span></td>
                </tr>
            `;
            return;
        }

        const gdpDiff = (cA.gdp.growth_num || 0) - (cB.gdp.growth_num || 0);
        const infDiff = (cA.inflation.score || 0) - (cB.inflation.score || 0);
        const riskDiff = (cA.risk.score || 0) - (cB.risk.score || 0);
        const weatherDiff = (cA.weather.score || 0) - (cB.weather.score || 0);
        const curDiff = Math.abs((cA.currency.score || 20) - (cB.currency.score || 20));

        tbody.innerHTML = `
            <!-- 1. GDP ROW -->
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td class="py-3 px-3 border-start border-primary border-4">
                    <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">GDP &amp; Pertumbuhan</div>
                    <div class="small" style="color:#3b82f6;">Economic Scale</div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#0f172a;">${cA.gdp.value}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#3b82f6;">+${cA.gdp.growth} Growth</span>
                        <span class="small" style="color:#64748b;">${cA.gdp.status}</span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-primary" style="width: ${cA.gdp.score}%"></div></div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#0f172a;">${cB.gdp.value}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#10b981;">+${cB.gdp.growth} Growth</span>
                        <span class="small" style="color:#64748b;">${cB.gdp.status}</span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-warning" style="width: ${cB.gdp.score}%"></div></div>
                </td>
                <td class="text-end py-3 px-3">
                    <div class="fw-semibold mb-1" style="font-size: 13px; color:${gdpDiff >= 0 ? '#10b981' : '#d97706'};">${gdpDiff >= 0 ? '+' : ''}${gdpDiff.toFixed(1)}% Growth Spread</div>
                    <div class="small fw-bold" style="color:#475569;">${gdpDiff >= 0 ? cA.name + ' Pertumbuhan Lebih Tinggi' : cB.name + ' Pertumbuhan Lebih Tinggi'}</div>
                </td>
            </tr>

            <!-- 2. INFLATION ROW -->
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td class="py-3 px-3 border-start border-danger border-4">
                    <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Inflasi &amp; Stabilitas Harga</div>
                    <div class="small" style="color:#ef4444;">Shock Index</div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#ef4444;">${cA.inflation.rate}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#64748b;">Risk: ${cA.inflation.score}/100</span>
                        <span class="small" style="color:#64748b;">${cA.inflation.status}</span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-danger" style="width: ${cA.inflation.score}%"></div></div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#d97706;">${cB.inflation.rate}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#64748b;">Risk: ${cB.inflation.score}/100</span>
                        <span class="small" style="color:#64748b;">${cB.inflation.status}</span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-warning" style="width: ${cB.inflation.score}%"></div></div>
                </td>
                <td class="text-end py-3 px-3">
                    <div class="fw-semibold mb-1" style="font-size: 13px; color:${infDiff <= 0 ? '#10b981' : '#ef4444'};">${infDiff > 0 ? '+' : ''}${infDiff.toFixed(1)} Pts Risk Spread</div>
                    <div class="small fw-bold" style="color:#475569;">${infDiff <= 0 ? cA.name + ' Lebih Stabil' : cB.name + ' Lebih Stabil'}</div>
                </td>
            </tr>

            <!-- 3. RISK ROW -->
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td class="py-3 px-3 border-start border-warning border-4">
                    <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Risiko Rantai Pasok</div>
                    <div class="small" style="color:#d97706;">Disruption Index</div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#10b981;">${cA.risk.score} / 100</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#64748b;">${cA.risk.level}</span>
                        <span class="small" style="color:#64748b;">Mar: <strong style="color:#0f172a;">${cA.risk.maritime}</strong> | Fin: <strong style="color:#0f172a;">${cA.risk.financial}</strong></span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-success" style="width: ${cA.risk.score}%"></div></div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#3b82f6;">${cB.risk.score} / 100</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#64748b;">${cB.risk.level}</span>
                        <span class="small" style="color:#64748b;">Mar: <strong style="color:#0f172a;">${cB.risk.maritime}</strong> | Fin: <strong style="color:#0f172a;">${cB.risk.financial}</strong></span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-info" style="width: ${cB.risk.score}%"></div></div>
                </td>
                <td class="text-end py-3 px-3">
                    <div class="fw-semibold mb-1" style="font-size: 13px; color:${riskDiff <= 0 ? '#10b981' : '#d97706'};">${riskDiff > 0 ? '+' : ''}${riskDiff.toFixed(1)} Pts Risk Spread</div>
                    <div class="small fw-bold" style="color:#475569;">${riskDiff <= 0 ? cA.name + ' Risiko Lebih Rendah' : cB.name + ' Risiko Lebih Rendah'}</div>
                </td>
            </tr>

            <!-- 4. WEATHER ROW -->
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td class="py-3 px-3 border-start border-info border-4">
                    <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Cuaca &amp; Pelabuhan</div>
                    <div class="small" style="color:#0ea5e9;">Maritime Transit</div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 18px; color:#0f172a;">${cA.weather.condition}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#0ea5e9;">Score: ${cA.weather.score}/100</span>
                        <span class="small" style="color:#64748b;">Wind: <strong style="color:#0f172a;">${cA.weather.wind_speed}</strong></span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-info" style="width: ${cA.weather.score}%"></div></div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 18px; color:#0f172a;">${cB.weather.condition}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#d97706;">Score: ${cB.weather.score}/100</span>
                        <span class="small" style="color:#64748b;">Wind: <strong style="color:#0f172a;">${cB.weather.wind_speed}</strong></span>
                    </div>
                    <div class="progress mx-auto" style="height: 5px; width: 80%; background: #e2e8f0; border-radius: 3px;"><div class="progress-bar bg-warning" style="width: ${cB.weather.score}%"></div></div>
                </td>
                <td class="text-end py-3 px-3">
                    <div class="fw-semibold mb-1" style="font-size: 13px; color:${weatherDiff <= 0 ? '#10b981' : '#0ea5e9'};">${weatherDiff > 0 ? '+' : ''}${weatherDiff.toFixed(1)} Pts Weather Spread</div>
                    <div class="small fw-bold" style="color:#475569;">${weatherDiff <= 0 ? cA.name + ' Cuaca Lebih Tenang' : cB.name + ' Cuaca Lebih Tenang'}</div>
                </td>
            </tr>

            <!-- 5. CURRENCY ROW -->
            <tr>
                <td class="py-3 px-3 border-start border-success border-4">
                    <div class="fw-bold mb-0" style="font-size: 15px; color:#0f172a;">Mata Uang &amp; Valas</div>
                    <div class="small" style="color:#10b981;">FX Volatility</div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#10b981;">${cA.currency.code.split(' - ')[0]}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#64748b;">Vol Risk: ${cA.currency.score}/100</span>
                        <span class="small fw-semibold" style="color:#10b981;">${cA.currency.trend}</span>
                    </div>
                </td>
                <td class="text-center py-3 px-3" style="border-right: 1px solid #f1f5f9;">
                    <div class="fw-bold mb-1" style="font-size: 20px; color:#3b82f6;">${cB.currency.code.split(' - ')[0]}</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 my-1">
                        <span class="small fw-semibold" style="color:#64748b;">Vol Risk: ${cB.currency.score}/100</span>
                        <span class="small fw-semibold" style="color:#10b981;">${cB.currency.trend}</span>
                    </div>
                </td>
                <td class="text-end py-3 px-3">
                    <div class="fw-semibold mb-1" style="font-size: 13px; color:#10b981;">${curDiff < 6 ? 'Low Spread' : (curDiff < 15 ? 'Moderate Spread' : 'High Differential')}</div>
                    <div class="small fw-bold" style="color:#475569;">${cA.currency.score <= cB.currency.score ? cA.name + ' FX Lebih Stabil' : cB.name + ' FX Lebih Stabil'}</div>
                </td>
            </tr>
        `;
    }

    function getFlagEmoji(iso) {
        if (!iso || iso === '--' || iso === 'UN') return '🌐';
        return iso.toUpperCase().replace(/./g, char => String.fromCodePoint(char.charCodeAt(0) + 127397));
    }

    // Start 60-second Auto-Refresh
    function startAutoRefresh() {
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
        autoRefreshInterval = setInterval(() => {
            fetch('/api/decision-support/data', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(result => {
                if (result.success && result.data) {
                    dssData = result.data;
                    const elClock = document.getElementById('dssClock');
                    if (elClock && result.data.header) elClock.textContent = result.data.header.timestamp;
                    const selectA = document.getElementById('selectCountryA');
                    const selectB = document.getElementById('selectCountryB');
                    if (selectA && selectB) {
                        renderComparisonEngine(dssData, selectA.value, selectB.value);
                    }
                }
            })
            .catch(err => console.error('Auto-refresh error:', err));
        }, 60000);
    }
});
