# 🚀 PLN K3 Inventaris - Docker Database Reset Script (PowerShell)
# This script will reset the database and create default users

Write-Host "🐳 PLN K3 Inventaris - Database Reset" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "⚠️  WARNING: This will DELETE ALL DATA in the database!" -ForegroundColor Yellow
Write-Host ""

$confirm = Read-Host "Are you sure you want to continue? (yes/no)"

if ($confirm -ne "yes") {
    Write-Host "❌ Cancelled." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "🔄 Starting database reset..." -ForegroundColor Yellow
Write-Host ""

# Run migration fresh with seeder
docker compose exec frankenphp php artisan migrate:fresh --seed

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✅ Database reset successful!" -ForegroundColor Green
    Write-Host ""
    Write-Host "📋 Default Login Credentials:" -ForegroundColor Cyan
    Write-Host "======================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "🔑 SUPERADMIN:" -ForegroundColor White
    Write-Host "   Email: superadmin@pln.co.id" -ForegroundColor Gray
    Write-Host "   Username: superadmin" -ForegroundColor Gray
    Write-Host "   Password: super123" -ForegroundColor Gray
    Write-Host ""
    Write-Host "👨‍💼 LEADER UPW2:" -ForegroundColor White
    Write-Host "   Email: leader.upw2@pln.co.id" -ForegroundColor Gray
    Write-Host "   Username: leader_upw2" -ForegroundColor Gray
    Write-Host "   Password: leader123" -ForegroundColor Gray
    Write-Host ""
    Write-Host "👷 PETUGAS UPW2:" -ForegroundColor White
    Write-Host "   Email: petugas.upw2@pln.co.id" -ForegroundColor Gray
    Write-Host "   Username: petugas_upw2" -ForegroundColor Gray
    Write-Host "   Password: petugas123" -ForegroundColor Gray
    Write-Host ""
    Write-Host "🔍 INSPECTOR:" -ForegroundColor White
    Write-Host "   Email: inspector@pln.co.id" -ForegroundColor Gray
    Write-Host "   Username: inspector" -ForegroundColor Gray
    Write-Host "   Password: inspector123" -ForegroundColor Gray
    Write-Host ""
    Write-Host "======================================" -ForegroundColor Cyan
    Write-Host "⚠️  Remember to change these passwords in production!" -ForegroundColor Yellow
    Write-Host ""
} else {
    Write-Host ""
    Write-Host "❌ Database reset failed!" -ForegroundColor Red
    Write-Host "Check the error messages above." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "💡 Troubleshooting tips:" -ForegroundColor Cyan
    Write-Host "   1. Make sure Docker containers are running: docker compose ps" -ForegroundColor Gray
    Write-Host "   2. Check database connection: docker compose logs mysql" -ForegroundColor Gray
    Write-Host "   3. Verify .env configuration inside container" -ForegroundColor Gray
    Write-Host ""
}
