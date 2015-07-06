from collections import deque

queue = deque(["anoop", "kumar", "singh"])
queue.append("dipika")
queue.append("myson")
aa = queue.popleft()

print aa

aa = queue.popleft()

print aa
