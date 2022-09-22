export default function toCurrency (value, minimumFractionDigits = 2) {
  if (typeof value !== 'number') {
    return value;
  }

  var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits
  });

  return formatter.format(value);
}

