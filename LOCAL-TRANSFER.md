# Перенос локального WordPress между Windows и Mac

Основной репозиторий кода: `https://github.com/renat1144/main2`.
Приватный ZIP дополняет GitHub и переносит базу WordPress, пользователей,
настройки, меню, `wp-content/uploads`, локальные `.env`/`wp-config.php`,
`PROJECT_HANDOFF.md`, актуальные файлы темы, плагинов и рабочие скрипты.
Благодаря этому архив сохраняет и локальные изменения, которые ещё не были
закоммичены. ZIP остаётся приватным и не заменяет обычную работу с GitHub.

Физически Docker-контейнеры на двух компьютерах разные. После импорта их
содержимое становится одинаковым.

## Где находятся архивы

Экспорт сохраняет ZIP прямо в папку `sites` на Google Drive, без вложенных
папок. На этом Windows-компьютере это:

```text
G:\Мой диск\sites
```

Пример имени:

```text
wordpress_site2-01-08-2026-153000-windows.zip
```

На Mac окончание имени будет `-mac.zip`. Рядом создаётся файл `.sha256`, по
которому импорт проверяет, что ZIP полностью синхронизировался и не повреждён.

## Экспорт на Windows

1. Запустите Docker Desktop и Google Drive Desktop.
2. Дважды запустите `FINISH_WORK.cmd` для обновления handoff и экспорта
   либо вручную выполните `script-local-export.cmd`.
3. Дождитесь, пока Google Drive закончит синхронизацию ZIP и `.sha256`.

## Импорт на Windows

1. Сначала клонируйте или обновите `https://github.com/renat1144/main2`.
2. Запустите Google Drive Desktop и дождитесь синхронизации папки `sites`.
3. Дважды запустите `START_WORK.cmd` либо вручную выполните
   `script-local-import.cmd` без аргументов.
4. Скрипт найдёт в `sites` самый новый архив `wordpress_site2-*.zip`, проверит
   его, скопирует в рабочую папку и запросит подтверждение словом `IMPORT`.

Перед заменой данных автоматически создаётся резервный ZIP в папке `backups`.
Docker volume не удаляется.

## Экспорт на Mac

Для штатного завершения работы в Finder дважды щёлкните:

```text
FINISH_WORK.command
```

Он обновит handoff и автоматически запустит `script-local-export.sh`. Ручная команда в Terminal также
остаётся доступной:

```bash
bash ./script-local-export.sh
```

Архив появится прямо в папке Google Drive `sites`. Дождитесь окончания
синхронизации Google Drive.

## Импорт на Mac

Сначала получите актуальный код из GitHub, затем в Finder дважды щёлкните:

```text
START_WORK.command
```

Он автоматически запустит `script-local-import.sh`. Ручной вариант:

```bash
bash ./script-local-import.sh
```

Скрипт автоматически возьмёт самый новый `wordpress_site2-*.zip` из `sites`,
проверит контрольную сумму, скопирует ZIP в проект и запросит `IMPORT`.

## Если Google Drive не найден автоматически

Windows:

```powershell
.\script-local-export.cmd -GoogleDriveSitesPath "G:\Мой диск\sites"
.\script-local-import.cmd -GoogleDriveSitesPath "G:\Мой диск\sites"
```

Mac:

```bash
bash ./script-local-export.sh --google-drive-sites-path "/путь/к/Google Drive/sites"
bash ./script-local-import.sh --google-drive-sites-path "/путь/к/Google Drive/sites"
```

## Важные правила

- ZIP содержит базу, учётные записи WordPress и локальные секреты. Он должен
  находиться только в вашей закрытой папке Google Drive и не загружаться в
  GitHub.
- Не редактируйте сайт одновременно на двух компьютерах. Более поздний импорт
  заменяет локальную базу состоянием из выбранного ZIP.
- На принимающем компьютере сначала обновляйте код через GitHub и только потом
  запускайте импорт ZIP.
- Импорт сначала создаёт локальный архив восстановления в `backups`, затем
  накладывает файлы проекта из ZIP и только после этого заменяет uploads и базу.
- Если нужно только проверить архив без изменения сайта, используйте
  `script-local-import.ps1 -ArchivePath <ZIP> -ValidateOnly` на Windows или
  `bash ./script-local-import.sh <ZIP> --validate-only` на Mac.
- Перед переходом на другой компьютер всегда создавайте новый экспорт на том
  устройстве, где находятся последние изменения.
