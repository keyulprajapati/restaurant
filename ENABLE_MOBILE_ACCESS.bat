@echo off
title Brahmani Lili Haldar - Enable Mobile QR Scanning
:: Batch script to allow inbound Apache HTTP (port 80) and Artisan (port 8000) through Windows Firewall
:: and switch Wi-Fi profile to Private network so mobile phones can connect seamlessly.

:: Check for Administrative permissions
net session >nul 2>&1
if %errorLevel% == 0 (
    goto :gotAdmin
) else (
    echo ====================================================================
    echo   Requesting Administrator privileges...
    echo   Please click "YES" in the Windows prompt that appears on screen.
    echo ====================================================================
    powershell -Command "Start-Process cmd -ArgumentList '/c %~s0' -Verb RunAs"
    exit /B
)

:gotAdmin
pushd "%CD%"
cd /d "%~dp0"

echo ====================================================================
echo   BRAHMANI LILI HALDAR - MOBILE QR ORDERING NETWORK SETUP
echo ====================================================================
echo.

echo [1/3] Adding Windows Firewall rule for Apache Web Server (Port 80)...
netsh advfirewall firewall delete rule name="WAMP_Apache_Port_80" >nul 2>&1
netsh advfirewall firewall add rule name="WAMP_Apache_Port_80" dir=in action=allow protocol=TCP localport=80 profile=any >nul
if %errorLevel% == 0 (
    echo       [OK] Port 80 is now ALLOWED for all inbound Wi-Fi connections.
) else (
    echo       [WARNING] Could not add port 80 rule.
)

echo.
echo [2/3] Adding Windows Firewall rule for Artisan / Alternative Server (Port 8000)...
netsh advfirewall firewall delete rule name="Laravel_Artisan_Port_8000" >nul 2>&1
netsh advfirewall firewall add rule name="Laravel_Artisan_Port_8000" dir=in action=allow protocol=TCP localport=8000 profile=any >nul
if %errorLevel% == 0 (
    echo       [OK] Port 8000 is now ALLOWED for all inbound Wi-Fi connections.
)

echo.
echo [3/3] Setting Active Wi-Fi network connection to 'Private' network...
powershell -Command "Get-NetConnectionProfile | Where-Object { $_.InterfaceAlias -match 'Wi-Fi' -or $_.IPv4Connectivity -eq 'Internet' } | Set-NetConnectionProfile -NetworkCategory Private -ErrorAction SilentlyContinue"
echo       [OK] Network profile set to Private (allows local device communication).

echo.
echo ====================================================================
echo   SETUP COMPLETE! YOUR MOBILE CAN NOW SCAN & ACCESS THE MENU!
echo ====================================================================
echo.
echo Your Local Wi-Fi Menu Address is:
echo   http://192.168.31.60/restaurant/public/table/T01
echo.
echo You can now scan the QR code with your mobile camera.
echo Press any key to close this window...
pause >nul
