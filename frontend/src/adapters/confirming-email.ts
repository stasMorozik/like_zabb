import { Either } from "@sweet-monads/either";
import { Subject, Observable } from 'rxjs';
import { ConfirmingEmailUseCase } from '../use-cases/confirming-email';
import { notAuthFetch } from './shared/not-auth.fetch';

export namespace ConfirmingEmailAdapters {
  export class Emiter implements ConfirmingEmailUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either)
    }
  }

  export class Api implements ConfirmingEmailUseCase.Ports.Api {
    fetch(dto: ConfirmingEmailUseCase.Dtos.Data): Observable<Either<Error, boolean>>  {
      return notAuthFetch<boolean>({
        url: '/api/confirmation-codes/',
        method: 'PUT',
        body: dto
      })
    }
  }
}
