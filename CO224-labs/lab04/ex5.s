    	.text
    	.global main

main:

	sub sp, sp, #4
	str lr, [sp, #0]

	@ -------------------------------------------------------

	SUB sp, sp, #8

	LDR r0, =formats	
	MOV r1, sp
	ADD r2, sp, #4		@ Here is the mistake we did, we need to call scanf(r0, r1=[sp], r2=[sp+4])

	BL scanf			@ scanf("%d %d", [sp], [sp+4]);		// here we need to provide 2 memory addresses

	LDR r0, =formatp
	LDR r1, [sp, #0]	@ then we can access values to r1 and r2 from [sp] and [sp+4] 
	LDR r2, [sp, #4]
	ADD sp, sp, #8

	BL printf			@ printf("Read: %d %d", r1, r2);	// here we need to provide 2 registers

	@ -------------------------------------------------------

	ldr lr, [sp, #0]
	add sp, sp, #4
	mov pc, lr

	.data

formats:
	.asciz "%d %d"
formatp:
    .asciz "Read: %d %d\n"

