# Stopwatch

Basic/naive implementation of a stopwatch using `hrtime`.

All non nanosecond representations are cast with (int), making them floor to the closest value.
toString representation is currently depending on the time elapsed, and if passed 1 on a given unit, it will display 
that unit with reminder of the value as a decimal.

If you use this package and need exact times, use the `nanosecond` value of the object.

