@echo off
setlocal
title Finish WordPress work
echo ========================================
echo  Finish WordPress work
echo ========================================
echo.
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0script-local-export.ps1" -UpdateHandoff
set "exit_code=%ERRORLEVEL%"
echo.
if "%exit_code%"=="0" goto export_success
echo Export failed. Review the message above.
goto export_done
:export_success
echo Archive created successfully in Google Drive - sites.
echo Wait until Google Drive reports Sync complete before switching devices.
goto export_done
:export_done
echo.
echo This window will close automatically in 10 seconds.
ping 127.0.0.1 -n 11 >nul
exit /b %exit_code%
