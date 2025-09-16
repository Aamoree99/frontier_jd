import os
import discord
from discord.ext import commands
from dotenv import load_dotenv

# Загружаем переменные из .env
load_dotenv()
TOKEN = os.getenv("DISCORD_TOKEN")
CHANNEL_ID = int(os.getenv("CHANNEL_ID"))
ROLE_ID = int(os.getenv("ROLE_ID"))
EMOJI = os.getenv("EMOJI")

# Настраиваем intents
intents = discord.Intents.default()
intents.guilds = True
intents.members = True
intents.reactions = True

bot = commands.Bot(command_prefix="!", intents=intents)

@bot.event
async def on_ready():
    print(f'Logged in as {bot.user}')

# Добавление роли при реакции
@bot.event
async def on_raw_reaction_add(payload):
    if payload.channel_id != CHANNEL_ID:
        return
    if str(payload.emoji) != EMOJI:
        return
    if payload.user_id == bot.user.id:
        return

    guild = bot.get_guild(payload.guild_id)
    member = guild.get_member(payload.user_id)
    role = guild.get_role(ROLE_ID)

    if role and member:
        await member.add_roles(role)
        print(f'Added role {role.name} to {member.display_name}')

# Удаление роли при снятии реакции
@bot.event
async def on_raw_reaction_remove(payload):
    if payload.channel_id != CHANNEL_ID:
        return
    if str(payload.emoji) != EMOJI:
        return
    if payload.user_id == bot.user.id:
        return

    guild = bot.get_guild(payload.guild_id)
    member = guild.get_member(payload.user_id)
    role = guild.get_role(ROLE_ID)

    if role and member:
        await member.remove_roles(role)
        print(f'Removed role {role.name} from {member.display_name}')

bot.run(TOKEN)
