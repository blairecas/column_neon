@echo off
echo.
echo ===========================================================================
echo Compiling graphics
echo ===========================================================================
..\..\php5\php.exe -c ..\..\php5\ -f convert_spr.php
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\..\php5\php.exe -c ..\..\php5\ -f convert_bgr.php
if %ERRORLEVEL% NEQ 0 ( exit /b )

echo.
echo ===========================================================================
echo Compiling CPU.MAC
echo ===========================================================================
..\..\php5\php.exe -c ..\..\php5\ -f ..\scripts\preprocess.php cpu.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\..\macro11\macro11.exe -ysl 32 -yus -m ..\..\macro11\sysmac.sml -l _cpu.lst _cpu.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )

echo.
echo ===========================================================================
echo Linking and cleanup
echo ===========================================================================
..\..\php5\php.exe -c ..\..\php5\ -f ..\scripts\lst2bin.php _cpu.lst ./release/column.sav sav 77777
del _cpu_bgr.dat
del _cpu_bgr_lz.dat
..\..\macro11\rt11dsk.exe d neon.dsk .\release\column.sav >NUL
..\..\macro11\rt11dsk.exe a neon.dsk .\release\column.sav >NUL

echo.