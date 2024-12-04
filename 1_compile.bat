@echo off
echo.
echo ===========================================================================
echo Compiling graphics
echo ===========================================================================
php -f convert_spr.php
if %ERRORLEVEL% NEQ 0 ( exit /b )
php -f convert_bgr.php
if %ERRORLEVEL% NEQ 0 ( exit /b )

echo.
echo ===========================================================================
echo Compiling CPU.MAC
echo ===========================================================================
php -f ..\scripts\preprocess.php cpu.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )
..\scripts\macro11 -ysl 32 -yus -m ..\scripts\sysmac.sml -l _cpu.lst _cpu.mac
if %ERRORLEVEL% NEQ 0 ( exit /b )

echo.
echo ===========================================================================
echo Linking and cleanup
echo ===========================================================================
php -f ..\scripts\lst2bin.php _cpu.lst ./release/column.sav sav 77777
if %ERRORLEVEL% NEQ 0 ( exit /b )

del _cpu_bgr.dat
del _cpu_bgr_lz.dat
del _cpu.mac
del _cpu.lst
del serial.log

..\scripts\rt11dsk d .\release\column.dsk column.sav >NUL
..\scripts\rt11dsk a .\release\column.dsk .\release\column.sav >NUL

echo.